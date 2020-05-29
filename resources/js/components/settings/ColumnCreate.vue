<template>
    <b-list-group-item>
        <b-form @submit.prevent="addCol">
            <b-input-group>
                <b-form-input v-model="header" placeholder="Column Header"></b-form-input>
                <b-input-group-append>
                    <b-button type="submit" variant="info"><i class="fa fa-plus"/></b-button>
                </b-input-group-append>
            </b-input-group>
        </b-form>
    </b-list-group-item>
</template>

<script>
    import {v4 as uuidv4} from 'uuid';

    export default {
        name: "ColumnCreate",

        props: {},

        data() {
            return {
                header: null,
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
                if(!this.header) {
                    this.$notify.alert('Please type in the header for the field');
                } else {
                    let col = Object.assign({}, this.defaultCol, {header: this.header});
                    let uuid = uuidv4();
                    this.$emit('create', uuid, col);
                    this.header = null
                }
            }
        },

        computed: {}
    }
</script>

<style scoped>

</style>