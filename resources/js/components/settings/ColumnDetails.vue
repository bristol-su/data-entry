<template>
    <div>
        <div v-if="hasColumn">
            <p-dynamic-form :schema="columnForm" v-model="column">

            </p-dynamic-form>

            <p-dynamic-form
                v-if="hasAdditionalConfiguration"
                :schema="configuration"
                v-model="filledConfiguration">
            </p-dynamic-form>

            <column-validation
                :value="column.validation"
                @input="setColValidation"
                :rules="rules">
            </column-validation>

        </div>
        <div v-else>
            Please select a column
        </div>
    </div>

</template>

<script>
    import ColumnValidation from './ColumnValidation';

    export default {
        name: "ColumnDetails",
        components: {ColumnValidation},
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

        methods: {
            setColValidation(validation) {
                let col = _.cloneDeep(this.column);
                col.validation = validation;
                this.column = col;
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
                            return {};
                        }
                        return this.column.configuration;
                    }
                    return null
                },
                set(val) {
                    let col = _.cloneDeep(this.column);
                    col.configuration = val;
                    this.column = col;
                }
            },
            fieldSelectOptions() {
                return this.fieldTypes.map(f => {
                    return {id: f.value, value: f.text};
                })
            },
            columnForm() {
                return this.$tools.generator.form.newForm()
                    .withGroup(
                        this.$tools.generator.group.newGroup()
                            .withField(
                                this.$tools.generator.field.checkbox('visible')
                                    .label('Column visible?')
                                    .value(true)
                                    .hint('Should the column be visible?')
                            )
                            .withField(
                                this.$tools.generator.field.text('header')
                                    .label('Column name')
                                    .hint('This will show in the table header')
                                    .required(true)
                            )
                            .withField(
                                this.$tools.generator.field.text('hint')
                                    .label('Column Hint')
                                    .hint('Some more information about what the field should contain')
                                    .required(true)
                            )
                            .withField(
                                this.$tools.generator.field.select('type')
                                    .label('Column Type')
                                    .hint('The type of the column')
                                    .required(true)
                                    .nullLabel('-- Please select a field type --')
                                    .setOptions(this.fieldSelectOptions)
                            )
                    )
            }
        }
    }
</script>

<style scoped>

</style>
