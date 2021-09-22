<template>
    <div>
        <p-api-form :schema="form" @submit="handleSubmit" :busy="$isLoading('upload-csv')" busy-text="Importing Template">

        </p-api-form>
    </div>
</template>

<script>
export default {
    name: "CsvUpload",

    methods: {
        handleSubmit(data) {
            let formData = new FormData();
            formData.append('file', data.file[0]);

            this.$http.post('/csv', formData, {headers: {'Content-Type': 'multipart/form-data'}, name: 'upload-csv'})
                .then(response => {
                    this.$notify.success('Csv Processed');
                    this.$emit('uploaded')
                    this.$emit('hide')
                })
                .catch(error => {
                    this.$notify.alert('Csv could not be processed: ' + error.response.data.message);
                    // if (error.response.status === 422) {
                    //     this.csvErrors = error.response.data.errors;
                    // } else {
                    // }
                })
                .then(() => {});
        }
    },

    computed: {
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
