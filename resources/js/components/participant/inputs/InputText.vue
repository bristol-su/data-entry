<template>
    <b-form-input
        v-model="contents"
        type="text"
        :state="isValid"
    ></b-form-input>
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