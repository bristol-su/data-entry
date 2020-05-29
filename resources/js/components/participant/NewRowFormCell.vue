<template>
    <b-form-group
            :id="uuid + '-group'"
            :label="label"
            :label-for="'input-' + uuid"
            :description="hint"
            :state="!hasErrors"
    >
        <div :id="'input-' + uuid" v-if="!hasComponent">Could not render input</div>
        <component 
            :id="'input-' + uuid"
            v-else
            :configuration="configuration"
            v-model="contents"
            :is-valid="isValid"
            :is="component"></component>

        <template v-slot:invalid-feedback>
            <ul>
                <li v-for="error of parsedErrors">{{error}}</li>
            </ul>
        </template>
    </b-form-group>
</template>

<script>
    import InputText from './inputs/InputText';
    import InputDate from './inputs/InputDate';
    import InputLongText from './inputs/InputLongText';
    import InputNumber from './inputs/InputNumber';
    import InputDecimal from './inputs/InputDecimal';
    import InputSelect from './inputs/InputSelect';
    import {debounce} from 'lodash';
    
    export default {
        name: "NewRowFormCell",
        
        components: {
            'input-text': InputText,
            'input-date': InputDate,
            'input-decimal': InputDecimal,
            'input-number': InputNumber,
            'input-longtext': InputLongText,
            'input-select': InputSelect
        },
        
        props: {
            uuid: {
                required: true,
                type: String
            },
            schema: {
                required: true,
                type: Object
            },
            value: {
                required: false,
                default: null
            },
            errors: {
                required: false,
                type: Array,
                default: function() {
                    return [];
                }
            }
        },

        data() {
            return {
                loading: false,
                checked: false,
                localErrors: []
            }
        },

        watch: {
            contents: {
                handler: function() {
                    this.validate();
                },
                deep: true
            }
        },
        
        methods: {
            validate: debounce(function() {
                this.loading = true;
                this.$http.post('/cell/' + this.uuid + '/validate', {
                    value: this.contents
                })
                    .then(response => {
                        if(response.status === 204) {
                            this.localErrors = [];
                        }
                        else {
                            this.localErrors = response.data;
                        }
                        this.checked = true;
                    })
                    .catch(error => this.$notify.alert('Validation could not be executed: ' + error.response.data.message))
                    .then(() => this.loading = false)
            }, 200)
        },

        computed: {
            contents: {
                get() {
                    return this.value;
                },
                set(value) {
                    this.$emit('input', value);
                }
            },
            label() {
                return this.schema.header ?? '';
            },
            hint() {
                return this.schema.hint ?? '';
            },
            component() {
                return 'input-' + this.schema.type;
            },
            hasComponent() {
                return this.component in this.$options.components;
            },
            configuration() {
                let configuration = this.schema.configuration;
                if(!configuration || configuration.length === 0) {
                    return {};
                }
                return configuration;
            },
            hasErrors() {
                return this.parsedErrors.length > 0;
            },
            parsedErrors() {
                if(this.errors.length > 0) {
                    return this.errors;
                }
                return this.localErrors;
            },
            isValid() {
                if(this.loading) {
                    return null;
                }
                if(this.checked && !this.hasErrors) {
                    return true;
                }
                if(this.hasErrors) {
                    return false;
                }
                return null;
            }
        }
    }
</script>

<style scoped>

</style>