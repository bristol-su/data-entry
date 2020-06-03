<template>
    <div>
        <b-row>
            <b-col>
                <b-table
                    :fields="fields"
                    :items="items"
                    no-local-sorting
                    :busy="loading">
                    <template v-slot:table-busy>
                        <div class="text-center text-danger my-2">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Loading...</strong>
                        </div>
                    </template>
                    <template v-slot:cell(name)="data">
                        <span v-if="data.item.resource_type === 'user'">{{data.item.participant.data.first_name}} {{data.item.participant.data.last_name}}</span>
                        <span v-if="data.item.resource_type === 'group'">{{data.item.participant.data.name}}</span>
                        <span v-if="data.item.resource_type === 'role'">{{data.item.participant.data.role_name}} ({{data.item.participant.data.position.name}} of {{data.item.participant.data.group.name}})</span>
                    </template>
                    <template v-slot:cell(actions)="data">
                        <b-button size="sm" variant="outline-info" @click="loadDetails(data)">
                            <i class="fa" :class="{'fa-eye': !data.detailsShowing, 'fa-eye-slash': data.detailsShowing}" />
                        </b-button>
                    </template>
                    <template v-slot:row-details="row">
                        <participant-table-wrapper
                            :can-update-row="canUpdateRow"
                            :can-store-row="canStoreRow"
                            :can-delete-row="canDeleteRow"
                            :activity-instance="row.item.id"    
                            :schema="schema">
                        </participant-table-wrapper>
                    </template>
                </b-table>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <b-pagination
                        :value="page"
                        @input="$emit('update:page', $event)"
                        :total-rows="totalPages * perPage"
                        :per-page="perPage"
                        first-text="First"
                        prev-text="Prev"
                        next-text="Next"
                        last-text="Last"
                        align="fill"
                ></b-pagination>
            </b-col>
            <b-col>
                <b-form-group
                    label="Per page"
                    label-for="perPageSelect"
                >
                    <b-form-select
                        :value="perPage"
                        @input="$emit('update:per-page', $event)"
                        id="perPageSelect"
                        size="sm"
                        :options="pageOptions"
                    ></b-form-select>
                </b-form-group>
            </b-col>
        </b-row>
        
    </div>
</template>

<script>
    import ParticipantTableWrapper from './ParticipantTableWrapper';
    
    export default {
        name: "AdminTable",
        components: {ParticipantTableWrapper},
        props: {
            schema: {
                required: true,
                type: Object
            },
            items: {
                required: true,
                type: Array
            },
            page: {
                required: true,
                type: Number
            },
            perPage: {
                required: true,
                type: Number
            },
            totalPages: {
                required: true,
                type: Number
            },
            loading: {
                required: false,
                type: Boolean,
                default: false
            },
            search: {
                required: false,
                type: String,
                default: null
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

        methods: {
            loadDetails(data) {
                data.toggleDetails();
                this.selectedActivityInstance = data.item.id;
            }
        },
       
        data() {
            return {
                pageOptions: [5, 10, 15, 20, 30, 40, 50],
                fields: [
                    {key: 'name', label: 'Name'},
                    {key: 'rows_count', label: 'Number of Rows'},
                    {key: 'actions', label: ''},
                ],
                selectedActivityInstance: null
            }
        },
    }
</script>

<style scoped>
    
</style>