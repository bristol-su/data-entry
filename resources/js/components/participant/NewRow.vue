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
            <new-row-form
                :errors="errors"
                v-model="newRow"
                :schema="schema"
                @submit="handleSubmit"
                v-if="!loading">
            </new-row-form>
            <div class="text-center" v-else>
                <b-spinner label="Spinning"></b-spinner>
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
    import NewRowForm from './NewRowForm';
    export default {
        name: "NewRow",
        components: {NewRowForm},
        props: {
            show: {
                required: true,
                type: Boolean
            },
            schema: {
                required: true,
                type: Object
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
                newRow: {},
                loading: false
            }
        },

        methods: {
            setRow(row) {
                this.newRow = row;
            },
            handleOk(bvModalEvt) {
                bvModalEvt.preventDefault()
                this.handleSubmit()
            },
            handleSubmit() {
                this.loading = true;
                // Validate
                this.$emit('submit', this.newRow, () => this.loading = false);
            },
            resetForm() {
                this.newRow = {};
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
            }
        }
    }
</script>

<style scoped>

</style>