<template>
    <p-number
        :id="id"
        :hint="hint"
        :label="label"
        :errors="errors"
        v-model="contents"
        step="0.01"
    ></p-number>
</template>

<script>
    import InputMixin from './InputMixin';

    export default {
        name: "InputDecimal",

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
