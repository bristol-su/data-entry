<template>
    <div>
            <p-api-form :schema="form" @submit="handleSubmit" v-if="!loading">

            </p-api-form>
            <div class="text-center" v-else>
                Loading...
            </div>

            <div v-if="hasErrors">
                Please fix the errors below, then re-upload the csv template.
                <ul>
                    <li v-for="error in errorArray">
                        {{error}}
                    </li>
                </ul>
            </div>
    </div>
</template>

<script>
    export default {
        name: "CsvUpload",

        props: {
            show: {
                required: true,
                type: Boolean
            },
            errors: {
                required: false,
                type: Object,
                default: function() {
                    return {};
                }
            }
        },

        data() {
            return {
                file: null,
                loading: false
            }
        },

        methods: {
            handleSubmit() {
                this.loading = true;
                this.$emit('submit', this.file, () => this.loading = false);
            }
        },

        computed: {
            hasErrors() {
                return this.errorArray.length > 0;
            },
            errorArray() {
                let errors = [];
                Object.keys(this.errors).forEach(error => {
                    errors = errors.concat(this.errors[error]);
                })
                return errors;
            },
            form() {
                return this.$tools.generator.form.newForm()
                    .withGroup(
                        this.$tools.generator.group.newGroup()
                            .withField(
                                this.$tools.generator.field.file('file')
                                    .label('Completed Template')
                                    .hint('Upload the completed template. The template can be downloaded from the previous screen.')
                            )
                    )
            }
        }
    }
</script>

<style scoped>

</style>
