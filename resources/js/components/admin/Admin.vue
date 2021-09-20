<template>
    <div>
        <p-tabs ref="tabs">
            <p-tab title="All">
                <div class="flex justify-end gap-2 self-end mb-2 mt-5">
                    <span>Actions: </span>
                    <a @click="$ui.modal.show('csv-download')" @keydown.enter.prevent="$ui.modal.show('csv-download')" @keydown.space.prevent="$ui.modal.show('csv-download')" v-if="canDownloadCsv" class="text-primary hover:text-primary-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             content="Download CSV"
                             v-tippy="{ arrow: true, animation: 'fade', placement: 'top-start', arrow: true, interactive: true}"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>
                </div>
                <p-table
                    :columns="allFields"
                    :items="items"
                    :busy="$isLoading('loading-activity-instances')"
                    :viewable="true"
                    @changePage="updatePageInformation"
                    @view="loadDetails"
                >
                    <template v-slot:cell(name)="{row}">
                        <span v-if="row.resource_type === 'user'">{{row.participant.data.first_name}} {{row.participant.data.last_name}}</span>
                        <span v-if="row.resource_type === 'group'">{{row.participant.data.name}}</span>
                        <span v-if="row.resource_type === 'role'">{{row.participant.data.role_name}} ({{row.participant.data.position.name}} of {{row.participant.data.group.name}})</span>
                    </template>
                </p-table>
            </p-tab>
            <p-tab title="Single">
                <div v-if="participantRow">
                    <h3 class="text-lg font-semibold text-center pb-4">
                        <span v-if="participantRow.resource_type === 'user'">{{participantRow.participant.data.preferred_name}}</span>
                        <span v-if="participantRow.resource_type === 'group'">{{participantRow.participant.data.name}}</span>
                        <span v-if="participantRow.resource_type === 'role'">{{participantRow.participant.data.role_name}} ({{participantRow.participant.data.position.name}} of {{participantRow.participant.data.group.name}})</span>
                    </h3>

                    <participant-table-wrapper
                        :can-update-row="canUpdateRow"
                        :can-store-row="canStoreRow"
                        :can-delete-row="canDeleteRow"
                        :activity-instance-id="participantRow.id"
                        :schema="columnSchema"
                        @delete="deleteItem">
                    </participant-table-wrapper>
                </div>
                <span v-else>
                    Please select which submission to view from the 'All' tab.
                </span>
            </p-tab>
        </p-tabs>

        <p-modal title="CSV Download" id="csv-download">
            <csv-download
                :activity-instances="items">
            </csv-download>
        </p-modal>

    </div>
</template>

<script>
    import {debounce} from 'lodash';
    import CsvDownload from './CsvDownload';
    import ParticipantTableWrapper from './ParticipantTableWrapper';

    export default {
        name: "Admin",
        components: {CsvDownload, ParticipantTableWrapper},
        props: {
            columnSchema: {
                required: true,
                type: Object
            },
            canDownloadCsv: {
                required: true,
                type: Boolean
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
                sortBy: null,
                sortDesc: false,
                search: null,
                errors: {},
                allFields: [
                    {key: 'participant_name', label: 'Name'},
                    {key: 'rows_count', label: 'Number of Rows'},
                ],
                participantRow: null
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
            loadDetails(row) {
                this.participantRow = row;
                this.$refs.tabs.selectTab(1);
            },
            updatePageInformation(pageData) {
                this.page = pageData.page;
                this.perPage = pageData.size;
            },
            loadItems() {
                this.$http.get('/activity-instance', {
                    params: this.urlParams,
                    name: 'loading-activity-instances'
                })
                    .then(response => {
                        this.items = response.data.data
                        this.page = response.data.current_page;
                        this.totalPages = response.data.last_page;
                    })
                    .catch(error => this.$notify.alert('Could not load row data: ' + error.response.data.message))
            },
            deleteItem() {
                if(this.participantRow) {
                    let participantRow = _.cloneDeep(this.participantRow);
                    participantRow.rows_count = (participantRow.rows_count === 0 ? 0 : participantRow.rows_count - 1);
                    this.items.splice(
                        this.items.indexOf(this.participantRow),
                        1,
                        participantRow
                    );
                }
            }
        },

        computed: {
            searchLoading() {
                return this.isLoading('loading-activity-instances') && this.search !== null && this.search !== ''
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
