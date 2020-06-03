<template>
    <span :class="{'matches-search': matchesSearch}">
        <span v-if="hasValue">{{value}}</span>
        <span v-else>N/A</span>
    </span>
</template>

<script>
    export default {
        name: "ParticipantTableCell",

        props: {
            value: {
                required: false,
                default: null                
            },
            search: {
                required: false,
                type: String,
                default: null
            }
        },

        data() {
            return {}
        },

        methods: {},

        computed: {
            hasValue() {
                return this.value !== null;
            },
            hasSearch() {
                return this.search !== null && this.search !== '';
            },
            matchesSearch() {
                return this.hasSearch && this.value.toLowerCase().includes(this.search.toLowerCase())
            }
        }
    }
</script>

<style scoped>
    .matches-search {
        background-color: yellow;
    }
</style>