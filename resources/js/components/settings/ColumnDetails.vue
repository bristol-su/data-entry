<template>
    <div>
        <div v-if="hasColumn">
            <b-form-group
                    description="Should the column be visible?"
                    id="visible-group"
                    label="Column visible:"
                    label-for="visible"
            >
                <b-form-checkbox
                        id="visible"
                        v-model="column.visible"
                        name="visible"
                >
                    {{visibleText}}
                </b-form-checkbox>
            </b-form-group>
            
            <b-form-group
                    description="This will show at the top of the table."
                    id="header-group"
                    label="Column Header:"
                    label-for="header"
            >
                <b-form-input
                        id="header"
                        placeholder="Enter Header"
                        required
                        type="text"
                        v-model="column.header"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    description="More information about what the field should contain."
                    id="hint-group"
                    label="Column hint:"
                    label-for="hint"
            >
                <b-form-input
                        id="hint"
                        placeholder="Enter hint"
                        required
                        type="text"
                        v-model="column.hint"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                    description="The type of the field."
                    id="type-group"
                    label="Column type:"
                    label-for="type"
            >
                <b-form-select
                        :options="fieldTypes"
                        id="type"
                        required
                        v-model="column.type">
                    <template v-slot:first>
                        <b-form-select-option :value="null" disabled>
                            -- Please select a field type --
                        </b-form-select-option>
                    </template>
                </b-form-select>
            </b-form-group>

            <vue-form-generator 
                v-if="hasAdditionalConfiguration"
                :schema="configuration.schema" 
                :model="filledConfiguration"
                :options="configuration.options">
            </vue-form-generator>

            <div class="border">
                <column-validation 
                    v-model="column.validation"
                    :rules="rules">
                    
                </column-validation>
            </div>
            
        </div>
        <div v-else>
            Please select a column
        </div>
    </div>

</template>

<script>
    import VueFormGenerator from 'vue-form-generator'
    import ColumnValidation from './ColumnValidation';
    
    export default {
        name: "ColumnDetails",
        components: {ColumnValidation, "vue-form-generator": VueFormGenerator.component},
        props: {
            value: {
                required: false,
                type: Object,
                default: null
            },
            fieldTypes: {
                required: false,
                type: Array,
                default: function() {
                    return [];
                }
            },
            globalRules: {
                required: false,
                type: Array,
                default: function() {
                    return {};
                }
            },
            fieldRules: {
                required: false,
                type: Object,
                default: function() {
                    return {};
                }
            },
            fieldConfiguration: {
                required: false,
                type: Object,
                default: function() {
                    return {};
                }
            }
        },

        computed: {
            column: {
                get() {
                    return this.value;
                },
                set(val) {
                    this.$emit('input', val);
                }
            },
            hasColumn() {
                return this.column !== null;
            },
            rules() {
                if(this.hasColumn) {
                    let rules = this.globalRules;
                    if(this.column.type && this.fieldRules.hasOwnProperty(this.column.type)) {
                        rules = rules.concat(this.fieldRules[this.column.type]);
                    }
                    return rules;
                }
                return null;
            },
            visibleText() {
                if(this.hasColumn) {
                    if(this.column.visible) {
                        return 'Visible';
                    }
                    return 'Hidden';    
                }
                return null;
            },
            hasAdditionalConfiguration() {
                return this.configuration !== null;
            },
            configuration() {
                if(this.hasColumn && this.column.type && this.fieldConfiguration.hasOwnProperty(this.column.type)) {
                    return this.fieldConfiguration[this.column.type];
                }
                return null;
            },
            filledConfiguration: {
                get() { 
                    if(this.hasColumn) {
                        if(Object.keys(this.column.configuration).length === 0) {
                            this.column.configuration = {};
                        }
                        return VueFormGenerator.schema.createDefaultObject(this.configuration.schema, this.column.configuration);
                    }
                    return null
                },
                set(val) {
                    this.column.configuration = val;
                }
            }
        }
    }
</script>

<style scoped>

</style>