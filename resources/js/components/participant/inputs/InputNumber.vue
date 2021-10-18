<template>
    <p-number
        :id="id"
        :hint="hint"
        :label="label"
        :errors="errors"
        v-model="contents"
    ></p-number>
</template>

<script>
    import InputMixin from './InputMixin';

    export default {
        name: "InputNumber",

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
