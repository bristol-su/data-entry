<template>
    <p-api-form :schema="form" @submit="handleSubmit" button-text="Download">

    </p-api-form>
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

        methods: {
            handleSubmit(data) {
                window.location.href = this.$tools.routes.query.addQueryStringToWebUrl(
                    this.$tools.routes.module.moduleUrl()
                    + '/csv'
                    + (data.dataset !== 'all-data' ? '/activity-instance/' + data.dataset : '')
                );
            }
        },

        computed: {
            options() {
                return [
                    {value: 'All Data', id: 'all-data'},
                    ...this.activityInstances.map(actInst => {
                        return {
                            id: actInst.id, value:
                                (actInst.resource_type === 'user' ? actInst.participant.data.first_name + ' ' + actInst.participant.data.last_name :
                                        (actInst.resource_type === 'group' ? actInst.participant.data.name : actInst.participant.data.role_name)
                                )
                        }
                    })
                ];
            },
            form() {
                return this.$tools.generator.form.newForm()
                    .withGroup(
                        this.$tools.generator.group.newGroup()
                            .withField(
                                this.$tools.generator.field.select('dataset')
                                    .label('What data set would you like to download?')
                                    .setOptions(this.options)
                                    .required(false)
                            )
                    )
            }
        }
    }
</script>

<style scoped>

</style>
