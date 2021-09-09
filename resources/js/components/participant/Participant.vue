<template>
    <div>
        <div class="flex justify-between">
            <div class="w-1/3">
                <p-text-input id="search" label="Search" hint="Type to search" v-model="search">

                </p-text-input>
            </div>
            <div class="flex justify-end gap-2 self-end mb-2">
                <span>Actions: </span>
                <a :href="csvUrl" v-if="downloadCsv" class="text-primary hover:text-primary-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         content="Download CSV"
                         v-tippy="{ arrow: true, animation: 'fade', placement: 'top-start', arrow: true, interactive: true}"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
                <a :href="templateUrl" v-if="useCsv" class="text-secondary hover:text-secondary-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         content="Download CSV Template"
                         v-tippy="{ arrow: true, animation: 'fade', placement: 'top-start', arrow: true, interactive: true}"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </a>
                <a href="#" @click="$ui.modal.show('upload-csv')" v-if="useCsv" class="text-info hover:text-info-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         content="Upload CSV File"
                         v-tippy="{ arrow: true, animation: 'fade', placement: 'top-start', arrow: true, interactive: true}"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </a>
            </div>
        </div>
        <div>
            <p-table
                :columns="fields"
                :total-count="items.length"
                :items="processedItems"
                :editable="canUpdateRow"
                :deletable="canDeleteRow"
                @edit="editRow"
                @delete="deleteRow"
                @changePage="updatePageInformation"
            >

                <template #cell()="{row, column}">
                    <participant-table-cell :search="search" :value="row.hasOwnProperty(column.key) ? row[column.key] : null">

                    </participant-table-cell>
                </template>
            </p-table>

            <div class="flex justify-end">
                <div class="w-1/3">
                    <p-button @click="$ui.modal.show('new-row')">Add new Row</p-button>
                </div>
            </div>
        </div>
        <div>
            <p-modal id="new-row" title="Add a new row">
                <new-row-form
                    :errors="errors"
                    v-model="newRow"
                    :schema="columnSchema"
                    @submit="processNewRow">
                </new-row-form>
            </p-modal>
        </div>
        <div>
            <p-modal id="edit-row" title="Edit a row">
                <new-row-form
                    v-if="newRow"
                    v-model="newRow"
                    :errors="errors"
                    :schema="columnSchema"
                    @submit="processNewRow">
                </new-row-form>
            </p-modal>
        </div>
        <div>
            <p-modal id="upload-csv" title="Add via CSV">
                <csv-upload
                    @submit="uploadCsv"
                    :errors="csvErrors">
                </csv-upload>
            </p-modal>


        </div>
    </div>
</template>

<script>
import {debounce} from 'lodash';
import CsvUpload from './CsvUpload';
import ParticipantTableCell from './ParticipantTableCell';
import NewRowForm from './NewRowForm';

export default {
    name: "Participant",
    components: {CsvUpload,ParticipantTableCell,NewRowForm},
    props: {
        columnSchema: {
            required: true,
            type: Object
        },
        useCsv: {
            required: true,
            type: Boolean,
        },
        downloadCsv: {
            required: true,
            type: Boolean,
        },
        canUpdateRow: {
            required: true,
            type: Boolean,
        },
        canStoreRow: {
            required: true,
            type: Boolean,
        },
        canDeleteRow: {
            required: true,
            type: Boolean,
        }
    },

    created() {
        this.loadItems();
    },

    data() {
        return {
            items: [],
            page: 1,
            perPage: 15,
            totalPages: 1,
            loading: false,
            search: null,
            errors: {},
            csvErrors: {},
            newRow: {}
        }
    },

    watch: {
        page() {
            this.loadItems();
        },
        perPage() {
            this.loadItems();
        },
        search: {
            handler: debounce(function () {
                this.loadItems();
            }, 500)
        },
    },

    methods: {
        updatePageInformation(pageData) {
            this.page = pageData.page;
            this.perPage = pageData.size;
        },
        editRow(row) {
            this.newRow = row;
            this.$ui.modal.show('edit-row');
        },
        deleteRow(row) {
            this.$ui.confirm.delete('Delete row?', 'Are you sure you want to delete this row?')
                .then(() => {
                    this.$http.delete('/row/' + row.row_id)
                        .then(response => {
                            this.$notify.success('Row deleted');
                            this.loadItems();
                        })
                        .catch(error => this.$notify.alert('Could not delete row: ' + error.response.data.message));
                });
        },
        loadItems() {
            this.loading = true;
            this.$http.get('/row', {
                params: this.urlParams
            })
                .then(response => {
                    this.items = response.data.data
                    this.page = response.data.current_page;
                    this.totalPages = response.data.last_page;
                })
                .catch(error => this.$notify.alert('Could not load row data: ' + error.response.data.message))
                .then(() => this.loading = false);
        },
        processNewRow() {
            if (this.newRow.hasOwnProperty('row_id')) {
                let row = Object.assign({}, this.newRow);
                delete row['row_id'];
                this.updateRow(this.newRow.row_id, row)
            } else {
                this.createNewRow(this.newRow);
            }
        },
        updateRow(id, row) {
            this.errors = {};
            this.$http.patch('/row/' + id, {
                fields: row
            })
                .then(response => {
                    this.$notify.success('Row Updated');
                    this.items.splice(
                        this.items.indexOf(this.items.find(item => item.id === id)),
                        1, response.data
                    );
                    this.$ui.modal.hide('edit-row');
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.csvErrors = error.response.data.errors;
                    } else {
                        this.$notify.alert('Row could not be updated: ' + error.response.data.message);
                    }
                })
                .then(() => {
                });
        },
        createNewRow(newRow) {
            this.errors = {};
            this.$http.post('/row', {
                fields: newRow
            })
                .then(response => {
                    this.$notify.success('Row Added');
                    this.items.unshift(response.data);
                    this.$ui.modal.hide('new-row');
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.$notify.alert('Row could not be added: ' + error.response.data.message);
                    }
                })
                .then(() => {
                });
        },
        uploadCsv(csv) {
            this.csvErrors = {};
            let formData = new FormData();
            formData.append('file', csv);

            this.$http.post('/csv', formData)
                .then(response => {
                    this.$notify.success('Csv Processed');
                    this.loadItems();
                    this.$ui.modal.hide('upload-csv');
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.csvErrors = error.response.data.errors;
                    } else {
                        this.$notify.alert('Csv could not be processed: ' + error.response.data.message);
                    }
                })
                .then(() => {});
        }
    },

    computed: {
        searchLoading() {
            return this.loading && this.search !== null && this.search !== ''
        },
        urlParams() {
            let params = {
                page: this.page,
                per_page: this.perPage
            }
            if (this.search !== null && this.search !== '') {
                params.search = this.search;
            }
            return params;
        },
        templateUrl() {
            return this.$tools.routes.query.addQueryStringToWebUrl(this.$tools.routes.module.moduleUrl() + '/csv/template');
        },
        csvUrl() {
            return this.$tools.routes.query.addQueryStringToWebUrl(this.$tools.routes.module.moduleUrl() + '/csv');
        },
        fields() {
            return Object.keys(this.columnSchema).filter(uuid => this.columnSchema[uuid].visible)
                .sort((a, b) => this.columnSchema[a].order - this.columnSchema[b].order)
                .map(uuid => {
                    let schema = this.columnSchema[uuid]
                    return {
                        key: uuid,
                        label: schema.header,
                        hint: schema.hint,
                        sortable: false
                    }
                });
        },
        processedItems() {
            return this.items.map(item => {
                let definition = {
                    row_id: item.id
                };
                item.cells.forEach(cell => {
                    definition[cell.column_id] = cell.value;
                })
                return definition;
            })
        }
    }
}
</script>

<style scoped>
.right-borderless {
    border-right: 0
}
</style>
