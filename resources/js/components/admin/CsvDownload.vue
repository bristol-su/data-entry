<template>
    <b-modal
            @hidden="resetForm"
            @ok="handleOk"
            id="new-row"
            scrollable
            size="xl"
            title="Add Row"
            v-model="shouldShowModal">
        <div style="padding-bottom: 30%">
            <b-row>
                <b-col>
                    <h3>Choose which data set to download</h3>
                </b-col>
            </b-row>
            <b-row>
                <b-col>

                    <b-form @submit.stop.prevent="handleSubmit">
                        <v-select :reduce="option => option.value" :options="options" v-model="download"></v-select>
                    </b-form>
                    <template v-slot:modal-cancel>
                        <span>Cancel</span>
                    </template>
                    <template v-slot:modal-ok>
                        <span>Save</span>
                    </template>
                </b-col>
            </b-row>
        </div>

    </b-modal>

</template>

<script>
    import VSelect from 'vue-select';

    export default {
        name: "CsvDownload",

        components: {VSelect},

        props: {
            show: {
                required: true,
                type: Boolean
            },
            queryString: {
                required: true,
                type: String
            },
            activityInstances: {
                required: false,
                type: Array,
                default() {
                    return [];
                }
            }
        },

        data() {
            return {
                download: null
            }
        },

        methods: {
            handleOk(bvModalEvt) {
                bvModalEvt.preventDefault()
                this.handleSubmit()
            },
            handleSubmit() {
                this.loading = true;
                // Validate
                window.location.href = this.downloadUrl
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
            },
            downloadUrl() {
                return this.$url
                    + '/csv'
                    + (this.download !== null ? '/activity-instance/' + this.download : '')
                    + '?'
                    + this.queryString;
            },
            options() {
                return [
                    {label: 'All Data', value: null},
                    ...this.activityInstances.map(actInst => {
                        return {
                            value: actInst.id, label:
                                (actInst.resource_type === 'user' ? actInst.participant.data.first_name + ' ' + actInst.participant.data.last_name :
                                        (actInst.resource_type === 'group' ? actInst.participant.data.name : actInst.participant.data.role_name)
                                )
                        }
                    })
                ];
            }
        }
    }
</script>

<style scoped>

</style>