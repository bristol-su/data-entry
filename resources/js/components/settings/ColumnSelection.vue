<template>
    <div>
        <draggable
                tag="div"
                class="list-group"
                v-model="localOrder"
                :animation="200"
                group="description"
                :disabled="false"
                @start="drag = true"
                @end="drag = false"
        >
            <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                <column-selection-button
                        v-for="(col, uuid) in cols"
                        :key="'key-' + uuid"
                        :col="col"
                        :uuid="uuid"
                        @click="$emit('input', uuid)"
                        :currentUuid="value">
        
                </column-selection-button>
            </transition-group>
        </draggable>
        <slot name="append">
            
        </slot>
    </div>
</template>

<script>
    import ColumnSelectionButton from './ColumnSelectionButton';
    import draggable from 'vuedraggable';
    
    export default {
        name: "ColumnSelection",
        components: {ColumnSelectionButton, draggable},
        props: {
            cols: {
                type: Object,
                default: function () {
                    return {};
                }
            },
            value: {
                type: String,
                default: null,
                required: false
            },
            order: {
                type: Array,
                default: function() {
                    return [];
                },
                required: false
            }
        },
        data() {
            return {
                drag: false
            }
        },
        computed: {
            localOrder: {
                get() {
                    return this.order;
                },
                set(val) {
                    this.$emit('update:order', val);
                }
            }
        }
    }
</script>

<style scoped>

</style>