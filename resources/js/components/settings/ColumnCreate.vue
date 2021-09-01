<template>
    <p-dynamic-form :schema="form" v-model="formData">
        <template #append>
            <p-button type="button" @click="addCol"><i class="fa fa-plus"/></p-button>
        </template>
    </p-dynamic-form>
</template>

<script>
    import {v4 as uuidv4} from 'uuid';

    export default {
        name: "ColumnCreate",

        props: {},

        data() {
            return {
                formData: {
                    header: null
                },
                defaultCol: {
                    header: null,
                    hint: null,
                    type: null,
                    configuration: {},
                    validation: [],
                    visible: true
                }
            }
        },

        methods: {
            addCol() {
                if(!this.formData.header) {
                    this.$notify.alert('Please type in the header for the field');
                } else {
                    let col = Object.assign({}, this.defaultCol, {header: this.formData.header});
                    let uuid = uuidv4();
                    this.$emit('create', uuid, col);
                    this.formData.header = null
                }
            }
        },

        computed: {
            form() {
                return this.$tools.generator.form.newForm()
                    .withField(
                        this.$tools.generator.field.text('header')
                            .label('Column Header')
                            .hint('What is the title for the column?')
                            .required(true)
                    )
            }
        }
    }
</script>

<style scoped>

</style>
