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
                <participant-table
                        :can-update-row="canUpdateRow"
                        :can-store-row="canStoreRow"
                        :can-delete-row="canDeleteRow"
                        @editRow="editRow"
                        @deleteRow="deleteRow"
                        @newRow="showNewRow = true"
                        :schema="schema"
                        :items="rows"
                        :page.sync="page"
                        :per-page.sync="perPage"
                        :total-pages.sync="totalPages"
                        :loading="loading">
                </participant-table>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <new-row
                        :show.sync="showNewRow"
                        :schema="schema"
                        @submit="processNewRow"
                        ref="newRow"
                        :errors="errors">
                </new-row>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import ParticipantTable from '../participant/ParticipantTable';
    import {debounce} from 'lodash';
    import NewRow from './../participant/NewRow';
    
    export default {
        name: "ParticipantTableWrapper",
        components: {NewRow, ParticipantTable},
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
                showNewRow: false,
                errors: {}
            }
        },

        methods: {

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
                        this.rows.splice(
                            this.rows.indexOf(this.rows.find(item => item.id === id)),
                            1, response.data
                        );
                        this.showNewRow = false;
                    })
                    .catch(error => {
                        if(error.response.status === 422) {
                            this.errors = error.response.data.errors;
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
                    activity_instance_id: this.activityInstance,
                    fields: newRow
                })
                    .then(response => {
                        this.$notify.success('Row Added');
                        this.rows.unshift(response.data);
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
            }
        }
    }
</script>

<style scoped>

</style>