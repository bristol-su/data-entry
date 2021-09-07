<template>
    <div style="border: 2px solid black">
        <b-row>
            <b-col></b-col>
            <b-col>
                <b-input-group>
                    <template v-slot:prepend>
                        <b-input-group-text>
                            <i class="fa fa-search" />
                        </b-input-group-text>
                    </template>
                    <b-form-input v-model="search" type="search" placeholder="Type to Search" :class="{'right-borderless': searchLoading}">

                    </b-form-input>
                    <template v-slot:append>
                        <b-input-group-text
                                style="border-left: 0; background-color: #ffffff"
                                v-if="searchLoading"
                                label="Loading">
                            <b-spinner small></b-spinner>
                        </b-input-group-text>
                    </template>
                </b-input-group>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <p-table
                    :columns="fields"
                    :total-count="rows.length"
                    :items="processedItems"
                    :editable="canUpdateRow"
                    :deletable="canDeleteRow"
                    @edit="editRow"
                    @delete="deleteRow"
                    @changePage="updatePageInformation"
                >

                    <template #cell()="{row}">
                        <participant-table-cell :search="search" :value="row.value">

                        </participant-table-cell>
                    </template>
                </p-table>

                <p-button @click="$ui.modal.show('new-row')">Add Row</p-button>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <p-modal id="new-row" title="Add a new row">
                    <new-row-form
                        :errors="errors"
                        v-model="newRow"
                        :schema="schema"
                        @submit="processNewRow">
                    </new-row-form>
                </p-modal>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import {debounce} from 'lodash';

    export default {
        name: "ParticipantTableWrapper",
        props: {
            activityInstance: {
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
                if(this.activityInstance) {
                    this.loadRows()
                }
            },
            perPage() {
                if(this.activityInstance) {
                    this.loadRows()
                }
            },
            search: {
                handler: debounce(function() {
                    if(this.activityInstance) {
                        this.loadRows()
                    }
                }, 500)
            }
        },

        mounted() {
            if(this.activityInstance) {
                this.loadRows();
            }
        },

        data() {
            return {
                loading: false,
                totalPages: 1,
                page: 1,
                perPage: 15,
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
                this.loading = true;
                this.$http.get('/activity-instance/' + this.activityInstance + '/row', {params: this.urlParams})
                    .then(response => {
                        this.rows = response.data.data
                        this.page = response.data.current_page;
                        this.totalPages = response.data.last_page;
                    })
                    .catch(error => this.$notify.alert('Could not load the rows: ' + error.response.data.message))
                    .then(() => this.loading = false);

            },
            editRow(row) {
                this.$refs.newRow.setRow(row)
                this.$ui.modal.show('new-row');
            },
            deleteRow(rowId) {
                this.$bvModal.msgBoxConfirm('Are you sure you want to delete this row?', {
                    title: 'Delete Row?',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'YES',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true,
                })
                    .then(value => {
                        if(value) {
                            this.$http.delete('/row/' + rowId)
                                .then(response => {
                                    this.$notify.success('Row deleted');
                                    this.loadRows();
                                })
                                .catch(error => this.$notify.alert('Could not delete row: ' + error.response.data.message));
                        }
                    })
            },
            processNewRow() {
                if(this.newRow.hasOwnProperty('row_id')) {
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
                        this.rows.splice(
                            this.rows.indexOf(this.rows.find(item => item.id === id)),
                            1, response.data
                        );
                        this.$ui.modal.hide('new-row');
                    })
                    .catch(error => {
                        if(error.response.status === 422) {
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
                    activity_instance_id: this.activityInstance,
                    fields: newRow
                })
                    .then(response => {
                        this.$notify.success('Row Added');
                        this.rows.unshift(response.data);
                        this.$ui.modal.hide('new-row');
                    })
                    .catch(error => {
                        if(error.response.status === 422) {
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
                if(this.search !== null && this.search !== '') {
                    params.search = this.search;
                }
                return params;
            },
            searchLoading() {
                return this.search !== '' && this.search !== null && this.loading === true;
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

</style>
