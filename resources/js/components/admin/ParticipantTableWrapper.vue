<template>
    <div>
        <p-table
            :columns="fields"
            :total-count="totalCount"
            :items="processedItems"
            :editable="canUpdateRow"
            :deletable="canDeleteRow"
            :busy="$isLoading('loading-single-row')"
            @edit="editRow"
            @delete="deleteRow"
            @changePage="updatePageInformation"
        >

            <template #cell()="{row,column}">
                <participant-table-cell :search="search" :value="row.hasOwnProperty(column.key) ? row[column.key] : null">

                </participant-table-cell>
            </template>
        </p-table>

        <div class="flex justify-end">
            <div class="w-1/2">
                <p-button @click="$ui.modal.show('new-row')">Add Row</p-button>
            </div>
        </div>

        <p-modal id="new-row" title="Add a new row">
            <new-row-form
                :errors="errors"
                v-model="newRow"
                :schema="schema"
                :busy="$isLoading('add-new-row')"
                busy-text="Adding Row"
                @submit="processNewRow">
            </new-row-form>
        </p-modal>

        <p-modal id="edit-row" title="Edit a row">
            <new-row-form
                v-if="newRow"
                v-model="newRow"
                :errors="errors"
                :schema="schema"
                busy-text="Updating Row"
                :busy="$isLoading('edit-row')"
                @submit="processNewRow">
            </new-row-form>
        </p-modal>
    </div>
</template>

<script>
import {debounce} from 'lodash';
import ParticipantTableCell from '../participant/ParticipantTableCell';
import NewRowForm from '../participant/NewRowForm';

export default {
    name: "ParticipantTableWrapper",
    components: {ParticipantTableCell, NewRowForm},
    props: {
        activityInstanceId: {
            required: false,
            type: Number,
            default: null
        },
        schema: {
            required: true,
            type: Object
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

    watch: {
        page() {
            if (this.activityInstanceId) {
                this.loadRows()
            }
        },
        perPage() {
            if (this.activityInstanceId) {
                this.loadRows()
            }
        },
        search: {
            handler: debounce(function () {
                if (this.activityInstanceId) {
                    this.loadRows()
                }
            }, 500)
        }
    },

    mounted() {
        if (this.activityInstanceId) {
            this.loadRows();
        }
    },

    data() {
        return {
            totalCount: 1,
            page: 1,
            perPage: 5,
            search: null,
            rows: [],
            errors: {},
            newRow: {}
        }
    },

    methods: {
        updatePageInformation(pageData) {
            this.page = pageData.page;
            this.perPage = pageData.size;
        },
        loadRows() {
            this.$http.get('/activity-instance/' + this.activityInstanceId + '/row', {params: this.urlParams, name: 'loading-single-row'})
                .then(response => {
                    this.rows = response.data.data
                    this.page = response.data.current_page;
                    this.totalCount = response.data.total;
                })
                .catch(error => this.$notify.alert('Could not load the rows: ' + error.response.data.message))

        },
        editRow(row) {
            this.newRow = row;
            this.$ui.modal.show('edit-row');
        },
        deleteRow(row) {
            this.$ui.confirm.delete('Delete row?', 'Are you sure you want to delete this row?')
                .then(() => {
                    this.$http.delete('/row/' + row.row_id, {name: 'delete-row-' + row.row_id})
                        .then(response => {
                            this.$notify.success('Row deleted');
                            this.loadRows();
                        })
                        .catch(error => this.$notify.alert('Could not delete row: ' + error.response.data.message));
                });
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
            }, {name: 'edit-row'})
                .then(response => {
                    this.$notify.success('Row Updated');
                    this.rows.splice(
                        this.rows.indexOf(this.rows.find(item => item.id === id)),
                        1, response.data
                    );
                    this.newRow = {};
                    this.$ui.modal.hide('edit-row');
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
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
                activity_instance_id: this.activityInstanceId,
                fields: newRow
            }, {name: 'add-new-row'})
                .then(response => {
                    this.$notify.success('Row Added');
                    this.rows.unshift(response.data);
                    this.$ui.modal.hide('new-row');
                    this.newRow = {};
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
        }
    },

    computed: {
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
        searchLoading() {
            return this.search !== '' && this.search !== null && this.$isLoading('loading-single-row');
        },
        fields() {
            return Object.keys(this.schema).filter(uuid => this.schema[uuid].visible)
                .sort((a, b) => this.schema[a].order - this.schema[b].order)
                .map(uuid => {
                    let schema = this.schema[uuid]
                    return {
                        key: uuid,
                        label: schema.header,
                        hint: schema.hint,
                        sortable: false
                    }
                });
        },
        processedItems() {
            return this.rows.map(item => {
                let definition = {
                    row_id: item.id,
                    _table: {
                        isDeleting: this.$isLoading('delete-row-' + item.id)
                    }
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

</style>
