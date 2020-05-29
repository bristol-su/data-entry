<template>
    <b-form-textarea
        v-model="contents"
        rows="3"
        :state="isValid"
    ></b-form-textarea>
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