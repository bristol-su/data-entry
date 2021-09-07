<template>
    <p-date-picker
        v-model="contents"
        :id="id"
        :hint="hint"
        :label="label"
        :errors="errors"
    ></p-date-picker>
</template>

<script>
    import InputMixin from './InputMixin';

    export default {
        name: "InputDate",

        mixins: [InputMixin],

        data() {
            return {}
        },

        methods: {},

        computed: {}
    }
</script>

<style scoped>

</style>
