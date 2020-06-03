<template>
    <div>
        <b-modal
                v-model="shouldShowModal"
                id="new-row"
                @hidden="resetForm"
                @ok="handleOk"
                size="xl"
                scrollable
                title="Add Row">
            <b-form @submit.stop.prevent="handleSubmit" v-if="!loading">
                <b-form-group
                        id="file-group"
                        description="Upload the completed template. The template can be downloaded from the previous screen."
                        label="Completed Template"
                        label-for="file"
                        :state="Boolean(file)"
                >
                    <b-form-file
                            id="file"
                            v-model="file"
                            placeholder="Choose a file or drop it here..."
                            drop-placeholder="Drop file here..."
                            accept="text/csv"
                    ></b-form-file>       
                </b-form-group>
            </b-form>            
            <div class="text-center" v-else>
                <b-spinner label="Spinning"></b-spinner>
            </div>
            
            <div v-if="hasErrors">
                Please fix the errors below, then re-upload the csv template.
                <ul>
                    <li v-for="error in errorArray">
                        {{error}}
                    </li>
                </ul>
            </div>
            
            <template v-slot:modal-cancel>
                <span v-if="loading"><b-spinner small></b-spinner></span>
                <span v-else>Cancel</span>
            </template>
            <template v-slot:modal-ok>
                <span v-if="loading"><b-spinner small></b-spinner></span>
                <span v-else>Save</span>
            </template>
        </b-modal>
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
            resetForm() {
                this.file = null
            },
            handleOk(bvModalEvt) {
                bvModalEvt.preventDefault()
                this.handleSubmit()
            },
            handleSubmit() {
                this.loading = true;
                this.$emit('submit', this.file, () => this.loading = false);
            }
        },

        computed: {
            shouldShowModal: {
                get() {
                    return this.show;
                },
                set(show) {
                    this.$emit('update:show', show);
                }
            },
            hasErrors() {
                return this.errorArray.length > 0;
            },
            errorArray() {
                let errors = [];
                Object.keys(this.errors).forEach(error => {
                    errors = errors.concat(this.errors[error]);
                })
                return errors;  
            }
        }
    }
</script>

<style scoped>

</style>