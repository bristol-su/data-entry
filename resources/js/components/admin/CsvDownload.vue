<template>

    <form @submit.stop.prevent="handleSubmit">
        <p-select id="csv-data-set" label="What data set would you like to download?" :select-options="options" v-model="download" :required="true"></p-select>

        <p-button variant="primary">Download</p-button>
    </form>
</template>

<script>
    import VSelect from 'vue-select';

    export default {
        name: "CsvDownload",

        components: {VSelect},

        props: {
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
            handleSubmit() {
                window.location.href = this.downloadUrl
            }
        },

        computed: {
            downloadUrl() {
                return this.$tools.routes.query.addQueryStringToWebUrl(
                    this.$tools.routes.module.moduleUrl()
                        + '/csv'
                        + (this.download !== null ? '/activity-instance/' + this.download : '')
                    );
            },
            options() {
                return [
                    {value: 'All Data', id: null},
                    ...this.activityInstances.map(actInst => {
                        return {
                            id: actInst.id, value:
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
