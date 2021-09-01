<template>
    <div>
        <p-checklist
            label="Column Validation"
            hint="Basic rules the column should adhere to"
            v-model="validation"
            :checklists="completeRuleSet"
            name="basic-rules"
            id="basic-rules"
            stacked
        ></p-checklist>
    </div>
</template>

<script>
export default {
    name: "ColumnValidationRuleSelect",
    props: {
        rules: {
            required: false,
            default: function() {
                return [];
            },
            type: Array
        },
        value: {
            required: false,
            type: Array,
            default: function() {
                return [];
            }
        }
    },
    data() {
        return {}
    },
    methods: {},
    computed: {
        validation: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            }
        },
        completeRuleSet() {
            let rules = this.rules.map(rule => {
                if(rule.value.includes(':')) {
                    let key = rule.value.split(':')[0];
                    let validation = this.validation.filter(val => val.includes(key + ':'));
                    if(validation.length > 0) {
                        rule.value = key + ':' + validation[0].split(':')[1]
                    }
                }
                return {id: rule.value, text: rule.text};
            })
            let extraRules = this.validation.filter(val => rules.filter(rule => rule.value === val).length === 0)
                .map(val => {
                    return {id: val, text: val.charAt(0).toUpperCase() + val.slice(1)}
                })
            return rules.concat(extraRules);
        }
    }
}
</script>

<style scoped>
</style>
