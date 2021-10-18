<template>
    <div>
        <column-validation-rule-select
            v-model="validation"
            :rules="rules">

        </column-validation-rule-select>

        <p-text-input
            v-for="(ruleParameter, index) in ruleParameters"
            :key="index"
            :label="ruleParameter.split(':')[0]"
            hint="Set paramaters for the rules"
            :required="true"
            :value="ruleParameter.split(':')[1]"
            @input="setValidationParameter(ruleParameter.split(':')[0], $event)"
            :id="'rule-parameter-' + index"
        ></p-text-input>

        <p-text-input
            id="validation"
            hint="Tweak the raw rules to customise the validation. Be careful using this tool!"
            label="Advanced Validation:"
            :required="true"
            type="text"
            v-model="stringableValidation"
        ></p-text-input>

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
