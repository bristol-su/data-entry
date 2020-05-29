<template>
    <div>
        <b-row>
            <b-col>
                <b-button variant="outline-secondary" @click="showCsvDownload = true">Download CSV</b-button>
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
                <admin-table
                        :schema="columnSchema"
                        :items="items"
                        :page.sync="page"
                        :per-page.sync="perPage"
                        :total-pages.sync="totalPages"
                        :sort-by.sync="sortBy"
                        :sort-desc.sync="sortDesc"
                        :search="search"
                        :loading="loading">

                </admin-table>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <csv-download
                    :show.sync="showCsvDownload"
                    :query-string="queryString"
                    :activity-instances="items">
                </csv-download>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import AdminTable from './AdminTable';
    import {debounce} from 'lodash';
    import CsvDownload from './CsvDownload';
    
    export default {
        name: "Admin",
        components: {CsvDownload, AdminTable},
        props: {
            columnSchema: {
                required: true,
                type: Object
            },
            queryString: {
                required: true,
                type: String
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
                sortBy: null,
                sortDesc: false,
                loading: false,
                search: null,
                showCsvDownload: false,
                errors: {},
            }
        },
        
        watch: {
            page() {
                this.loadItems();
            },
            perPage() {
                this.loadItems();
            },
            sortBy() {
                this.loadItems();
            },
            sortDesc() {
                this.loadItems();
            },
            search: {
                handler: debounce(function() {
                    this.loadItems();
                }, 500)
            },
        },

        methods: {
            loadItems() {
                this.loading = true;
                this.$http.get('/activity-instance', {
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
                if(this.sortBy !== null) {
                    params.sort_by = this.sortBy;
                    params.sort_desc = this.sortDesc;
                }
                if(this.search !== null && this.search !== '') {
                    params.search = this.search;
                }
                return params;
            }
        }
    }
</script>

<style scoped>
    .right-borderless {
        border-right: 0
    }
</style>