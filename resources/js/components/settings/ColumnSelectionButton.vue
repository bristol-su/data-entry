<template>
    <b-list-group-item
            href="#"
            :active="isActive"
            active-class="btn btn-info"
            @click="$emit('click', uuid)"
    >
        <i class="fa fa-bars"/> {{header}} <i class="fa fa-chevron-right fa-pull-right"/>
    </b-list-group-item>
</template>

<script>
    export default {
        name: "ColumnSelectionButton",

        props: {
            col: {
                type: Object,
                required: true
            },
            uuid: {
                required: true,
                type: String
            },
            currentUuid: {
                required: false,
                type: String,
                default: null
            }
        },

        computed: {
            header() {
                if(this.col.hasOwnProperty('header')) {
                    return this.col.header;
                }
                return 'No header';
            },
            isActive() {
                return this.uuid === this.currentUuid;
            }
        }
    }
</script>

<style scoped>

</style>