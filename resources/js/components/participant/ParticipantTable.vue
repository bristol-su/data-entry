<template>
    <div>
        <b-row>
            <b-col>
                <b-table
                    :fields="fields"
                    :items="processedItems"
                    no-local-sorting
                    :busy="loading">
                    <template v-slot:bottom-row="{columns}">
                        <td :colspan="columns">
                            <span style="text-align: right;">
                                <b-button @click="$emit('newRow')" variant="outline-info" size="lg" style="width: 100%">Add Row</b-button>
                            </span>
                        </td>
                    </template>
                    <template v-slot:table-busy>
                        <div class="text-center text-danger my-2">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Loading...</strong>
                        </div>
                    </template>
                    <template v-slot:cell(actions)="data">
                        <b-button variant="outline-info" size="sm" @click="$emit('editRow', data.item)"><i class="fa fa-edit"/></b-button>
                        <b-button variant="outline-danger" size="sm" @click="$emit('deleteRow', data.item.row_id)"><i class="fa fa-trash"/></b-button>
                    </template>
                    <template v-slot:cell()="data">
                        <participant-table-cell :search="search" :value="data.value">
                            
                        </participant-table-cell>
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
    import ParticipantTableCell from './ParticipantTableCell';
    export default {
        name: "ParticipantTable",
        components: {ParticipantTableCell},
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
        },

        data() {
            return {
                pageOptions: [5, 10, 15, 20, 30, 40, 50],
                baseFields: [
                    {key: 'actions', label: ''}
                ]
            }
        },

        computed: {
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
                }).concat(this.baseFields)
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