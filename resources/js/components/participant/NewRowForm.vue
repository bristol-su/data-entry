<template>
    <b-form @submit.stop.prevent="$emit('submit')">
        <new-row-form-cell
            v-for="uuid in orderedAndFilteredKeys"
            :key="uuid"
            :uuid="uuid"
            :schema="schema[uuid]"
            v-model="form[uuid]"
            :errors="errors.hasOwnProperty('fields.' + uuid) ? errors['fields.' + uuid] : []">
            
        </new-row-form-cell>
    </b-form>
</template>

<script>
    import NewRowFormCell from './NewRowFormCell';
    export default {
        name: "NewRowForm",
        components: {NewRowFormCell},
        props: {
            value: {
                required: true,
                type: Object
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
            return {}
        },

        methods: {},

        computed: {
            form: {
                get() {
                    return this.value;
                },
                set(value) {
                    this.$emit('input', value);
                }
            },
            orderedKeys() {
                return Object.keys(this.schema).sort((a, b) => this.schema[a].order - this.schema[b].order);
            },
            orderedAndFilteredKeys() {
                return this.orderedKeys.filter(uuid => this.schema[uuid].visible);
            }
        }
    }
</script>

<style scoped>

</style>