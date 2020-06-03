<template>
    <b-form-checkbox-group
            v-model="validation"
            :options="completeRuleSet"
            name="basic-rules"
            id="basic-rules"
            stacked
    ></b-form-checkbox-group>
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
                    return rule;
                })
                let extraRules = this.validation.filter(val => rules.filter(rule => rule.value === val).length === 0)
                    .map(val => {
                        return {value: val, text: val.charAt(0).toUpperCase() + val.slice(1)}
                    })
                return rules.concat(extraRules);
            }
        }
    }
</script>

<style scoped>

</style>