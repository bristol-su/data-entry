<template>
    <p-textarea
        :id="id"
        :hint="hint"
        :label="label"
        :errors="errors"
        v-model="contents"
    ></p-textarea>
</template>

<script>
    import InputMixin from './InputMixin';

    export default {
        name: "InputLongText",

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
