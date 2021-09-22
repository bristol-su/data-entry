<template>
    <p-text-input
        v-model="contents"
        :id="id"
        :hint="hint"
        :label="label"
        :errors="errors"
    ></p-text-input>
</template>

<script>
    import InputMixin from './InputMixin';

    export default {
        name: "InputText",

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
