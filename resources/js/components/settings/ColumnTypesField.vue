<template>
    <b-container>
        <b-row>
            <b-col>
                <column-selection 
                    :cols="orderedColumns"
                    v-model="selectedUuid"
                    :order.sync="order"
                >
                    <template v-slot:append>
                        <column-create @create="appendColumn">
                            
                        </column-create>
                    </template>
                </column-selection>
            </b-col>
            <b-col>
                <column-details 
                    v-model="column"
                    :field-types="fieldTypes"
                    :global-rules="globalRules"
                    :field-rules="fieldRules"
                    :field-configuration="fieldConfiguration">
                    
                </column-details>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import { abstractField } from 'vue-form-generator';
    import ColumnSelection from './ColumnSelection';
    import ColumnDetails from './ColumnDetails';
    import ColumnCreate from './ColumnCreate';
    
    export default {
        name: "ColumnTypesField",
        components: {ColumnCreate, ColumnDetails, ColumnSelection},
        props: {},

        mixins: [abstractField],
        
        data() {
            return {
                selectedUuid: null,
            }
        },

        methods: {
            clearValidationErrors() {
                
            },
            appendColumn(uuid, column) {
                this.$set(this.columns, uuid, column);
                this.order.push(uuid);
            }
        },
        
        computed: {
            order: {
                get() {
                    return Object.keys(this.columns).sort((a, b) => this.columns[a].order - this.columns[b].order);
                },
                set(val) {
                    let cols = {};
                    val.forEach((uuid, index) => {
                        cols[uuid] = {
                            ...this.columns[uuid],
                            ...{order: index + 1}
                        }
                    })
                    this.columns = cols;
                }
            },
            columns: {
                get() {
                    if(this.value) {
                        return this.value;
                    }
                    this.value = {};
                    return this.columns;
                },
                set(val) {
                    this.value = val;
                }
            },
            orderedColumns() {
                let cols = {};
                this.order.forEach(uuid => cols[uuid] = this.columns[uuid]);
                return cols;
            },
            column: {
                get() {
                    if(this.selectedUuid) {
                        return this.columns[this.selectedUuid];
                    }
                    return null;
                },
                set(val) {
                    this.$set(this.columns, this.selectedUuid, val);
                }
            },
            fieldTypes() {
                if(this.schema.fieldTypes) {
                    return this.schema.fieldTypes;
                }
                return []
            },
            globalRules() {
                if(this.schema.globalValidation) {
                    return this.schema.globalValidation;
                }
                return []
            },
            fieldRules() {
                if(this.schema.fieldValidation) {
                    return this.schema.fieldValidation;
                }
                return {}
            },
            fieldConfiguration() {
                if(this.schema.fieldConfiguration) {
                    return this.schema.fieldConfiguration;
                }
                return {};
            },
            nextInOrder() {
                let keys = Object.keys(this.columns);
                if(keys.length > 0) {
                    return this.columns[keys[keys.length - 1]].order + 1;
                }
                return 1;
            }
        }

    }
</script>

<style scoped>

</style>