<template>
    <b-form-input
        v-model="contents"
        type="number"
        step="0.01"
        :state="isValid"
    ></b-form-input>
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