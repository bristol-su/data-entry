<template>
    <div>
        <b-row>
            <b-col>
                <b-button variant="outline-secondary" :href="csvUrl" v-if="downloadCsv">Download CSV</b-button>
                <b-button variant="outline-secondary" :href="templateUrl" v-if="useCsv">Download CSV Template</b-button>
                <b-button variant="outline-secondary" @click="showCsvUpload = true" v-if="useCsv">Upload CSV</b-button>
            </b-col>
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
                <participant-table
                        :can-update-row="canUpdateRow"
                        :can-store-row="canStoreRow"
                        :can-delete-row="canDeleteRow"
                        :schema="columnSchema"
                        :items="items"
                        :page.sync="page"
                        :per-page.sync="perPage"
                        :total-pages.sync="totalPages"
                        @newRow="showNewRow = true"
                        @editRow="editRow"
                        @deleteRow="deleteRow"
                        :search="search"
                        :loading="loading">

                </participant-table>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <new-row
                        :show.sync="showNewRow"
                        :schema="columnSchema"
                        @submit="processNewRow"
                        ref="newRow"
                        :errors="errors">
                </new-row>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <csv-upload
                        :show.sync="showCsvUpload"
                        @submit="uploadCsv"
                        :errors="csvErrors">
                </csv-upload>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import ParticipantTable from './ParticipantTable';
    import NewRow from './NewRow';
    import {debounce} from 'lodash';
    import CsvUpload from './CsvUpload';
    
    export default {
        name: "Participant",
        components: {CsvUpload, NewRow, ParticipantTable},
        props: {
            columnSchema: {
                required: true,
                type: Object
            },
            queryString: {
                required: true,
                type: String
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
                showNewRow: false,
                loading: false,
                search: null,
                errors: {},
                showCsvUpload: false,
                csvErrors: {}
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
                handler: debounce(function() {
                    this.loadItems();
                }, 500)
            },
        },

        methods: {
            editRow(row) {
                this.$refs.newRow.setRow(row)
                this.showNewRow = true;
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
                    centered: true
                })
                    .then(value => {
                        if(value) {
                            this.$http.delete('/row/' + rowId)
                                .then(response => {
                                    this.$notify.success('Row deleted');
                                    this.loadItems();
                                })
                                .catch(error => this.$notify.alert('Could not delete row: ' + error.response.data.message));
                        }
                    })
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
            processNewRow(newRow, stopLoading) {
                if(newRow.hasOwnProperty('row_id')) {
                    let row = Object.assign({}, newRow);
                    delete row['row_id'];
                    this.updateRow(newRow.row_id, row, stopLoading)
                } else {
                    this.createNewRow(newRow, stopLoading);
                }
            },
            updateRow(id, row, stopLoading) {
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
                        this.showNewRow = false;
                    })
                    .catch(error => {
                        if(error.response.status === 422) {
                            this.csvErrors = error.response.data.errors;
                        } else {
                            this.$notify.alert('Row could not be updated: ' + error.response.data.message);
                        }
                    })
                    .then(() => {
                        stopLoading()
                    });
            },
            createNewRow(newRow, stopLoading) {
                this.errors = {};
                this.$http.post('/row', {
                    fields: newRow
                })
                    .then(response => {
                        this.$notify.success('Row Added');
                        this.items.unshift(response.data);
                        this.showNewRow = false;
                    })
                    .catch(error => {
                        if(error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            this.$notify.alert('Row could not be added: ' + error.response.data.message);
                        }
                    })
                    .then(() => {
                        stopLoading()
                    });
            },
            uploadCsv(csv, stopLoading) {
                this.csvErrors = {};
                let formData = new FormData();
                formData.append('file', csv);
                
                this.$http.post('/csv' , formData)
                    .then(response => {
                        this.$notify.success('Csv Processed');
                        this.loadItems();
                        this.showCsvUpload = false;
                    })
                    .catch(error => {
                        if(error.response.status === 422) {
                            this.csvErrors = error.response.data.errors;
                        } else {
                            this.$notify.alert('Csv could not be processed: ' + error.response.data.message);
                        }
                    })
                    .then(() => stopLoading());
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
                if(this.search !== null && this.search !== '') {
                    params.search = this.search;
                }
                return params;
            },
            templateUrl() {
                return this.$url + '/csv/template?' + this.queryString;
            },
            csvUrl() {
                return this.$url + '/csv?' + this.queryString;
            }
        }
    }
</script>

<style scoped>
    .right-borderless {
        border-right: 0
    }
</style>