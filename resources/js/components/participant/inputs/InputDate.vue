<template>
    <b-form-datepicker 
        v-model="contents"
        :state="isValid"
        today-button
        reset-button
        close-button
        calendar-width="100%"
    ></b-form-datepicker>
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