<template>
    <p-select
        :select-options="options"
        v-model="contents"
        :add-own-options="canAddOptions"
        class="form-control"
        :id="id"
        :hint="hint"
        :label="label"
        :errors="errors"
        :class="extraClasses"
        style="height: auto;">
    </p-select>
</template>

<script>
    import InputMixin from './InputMixin';
    import 'vue-select/dist/vue-select.css';
    import VSelect from 'vue-select';

    export default {
        name: "InputSelect",

        mixins: [InputMixin],

        components: {
            VSelect
        },

        data() {
            return {}
        },

        methods: {},

        computed: {
            options() {
                return this.configuration.hasOwnProperty('select_options') ? this.configuration.select_options.map(opt => {
                    return {id: opt, value: opt}
                }) : [];
            },
            canAddOptions() {
                return this.configuration.hasOwnProperty('add_own') ? this.configuration.add_own : false;
            },
            extraClasses() {
                let classes = [];
                if(this.isValid) {
                    classes.push('is-valid');
                } else if(this.isValid === false) {
                    classes.push('is-invalid');
                }
                return classes;
            }
        }
    }
</script>

<style scoped>

</style>
