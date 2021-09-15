<template>
    <div>
                <p-table
                    :columns="fields"
                    :items="items"
                    v-if="!loading"
                    :viewable="true"
                    @view="loadDetails(data)">
                    <template v-slot:cell(name)="{row}">
                        <span v-if="row.resource_type === 'user'">{{row.participant.data.first_name}} {{row.participant.data.last_name}}</span>
                        <span v-if="row.resource_type === 'group'">{{row.participant.data.name}}</span>
                        <span v-if="row.resource_type === 'role'">{{row.participant.data.role_name}} ({{row.participant.data.position.name}} of {{row.participant.data.group.name}})</span>
                    </template>
                </p-table>
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
