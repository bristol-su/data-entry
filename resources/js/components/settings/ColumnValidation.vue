<template>
    <div>
        <b-form-group
                description="Basic rules the column should adhere to"
                id="basic-rules-group"
                label="Column validation:"
                label-for="basic-rules"
        >
            <div
                    class="bg-light border p-2"
                    style="height: 85px; overflow-y: scroll;"
            >
                <column-validation-rule-select
                    v-model="validation"
                    :rules="rules">
                    
                </column-validation-rule-select>
            </div>
        </b-form-group>

        <b-form-group
                description="Parameters for the above rules"
                id="rule-parameter-group"
                label="Column validation:"
                label-for="rule-parameter"
        >

            <b-input-group
                    v-for="(ruleParameter, index) in ruleParameters"
                    :key="index"
                    :prepend="ruleParameter.split(':')[0]">
                <b-form-input
                        placeholder="Enter Parameter"
                        required
                        :value="ruleParameter.split(':')[1]"
                        @input="setValidationParameter(ruleParameter.split(':')[0], $event)"
                        type="text"
                        name="rule-parameter"
                        id="rule-parameter"
                ></b-form-input>
            </b-input-group>

        </b-form-group>

        <b-form-group
                description="Tweak the raw rules to customise the validation. Be careful using this tool!"
                id="validation-group"
                label="Advanced Validation:"
                label-for="validation"
        >
            <b-form-input
                    id="validation"
                    placeholder="Select validation above"
                    required
                    type="text"
                    v-model="stringableValidation"
            ></b-form-input>
        </b-form-group>
    </div>
</template>

<script>
    import ColumnValidationRuleSelect from './ColumnValidationRuleSelect';
    export default {
        name: "ColumnValidation",
        components: {ColumnValidationRuleSelect},
        props: {
            value: {
                required: false,
                type: Array,
                default: function() {
                    return [];
                }
            },
            rules: {
                required: false,
                type: Array,
                default: function() {
                    return [];
                }
            }
        },

        methods: {
            setValidationParameter(key, parameter) {
                let index = this.validation.indexOf(this.validation.find(val => val.includes(key + ':')))
                if(index !== -1) {
                    this.validation.splice(index, 1, key + ':' + parameter);
                }
            }
        },

        computed: {
            validation: {
                get() {
                    return this.value;
                },
                set(val) {
                    this.$emit('input', val);
                }
            },
            stringableValidation: {
                get() {
                    return (this.validation ?? []).join('|');
                },
                set(val) {
                    this.validation = val.split('|')
                }
            },
            ruleParameters() {
                return this.validation.filter(val => val.includes(':'));
            }
        }
    }
</script>

<style scoped>

</style>