<template>
    <b-form-input
        v-model="contents"
        type="number"
        :state="isValid"
    ></b-form-input>
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