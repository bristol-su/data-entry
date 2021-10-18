<template>
    <div>
        <column-selection
            :cols="orderedColumns"
            v-model="selectedUuid"
            :order.sync="order"
        >
            <template v-slot:append>
                <column-create @create="appendColumn">

                </column-create>
            </template>
        </column-selection>

        <column-details
            v-model="column"
            :field-types="fieldTypes"
            :global-rules="globalValidation"
            :field-rules="fieldValidation"
            :field-configuration="fieldConfiguration">

        </column-details>
    </div>
</template>

<script>
import FormInputMixin from '@bristol-su/portal-ui-kit/src/components/atomic/dynamic-form/FormInputMixin';
import ColumnSelection from './ColumnSelection';
import ColumnDetails from './ColumnDetails';
import ColumnCreate from './ColumnCreate';

export default {
    name: "ColumnTypesField",
    components: {ColumnCreate, ColumnDetails, ColumnSelection},
    props: {
        fieldTypes: {
            required: false, type: Array, default: () => []
        },
        globalValidation: {
            required: false, type: Array, default: () => []
        },
        fieldValidation: {
            required: false, type: Object, default: () => {}
        },
        fieldConfiguration: {
            required: false, type: Object, default: () => {}
        },
    },

    mixins: [FormInputMixin],

    data() {
        return {
            selectedUuid: null,
        }
    },

    methods: {
        clearValidationErrors() {

        },
        appendColumn(uuid, column) {
            let col = _.cloneDeep(this.columns);
            col[uuid] = column;
            this.columns = col;
            this.order.push(uuid);
        }
    },

    computed: {
        order: {
            get() {
                return Object.keys(this.columns).sort((a, b) => this.columns[a].order - this.columns[b].order);
            },
            set(val) {
                let cols = {};
                val.forEach((uuid, index) => this.$set(cols, uuid, {
                    ...this.columns[uuid],
                    ...{order: index + 1}
                }));
                this.columns = cols;
            },
            cache: false
        },
        columns: {
            get() {
                if (this.dynamicValue) {
                    return this.dynamicValue;
                }
                return {};
            },
            set(val) {
                this.dynamicValue = val;
            },
            cache: false
        },
        orderedColumns: {
            get() {
                let cols = {};
                this.order.forEach(uuid => this.$set(cols, uuid, this.columns[uuid]));
                return cols;
            },
            cache: false
        },
        column: {
            get() {
                if (this.selectedUuid) {
                    return this.columns[this.selectedUuid];
                }
                return null;
            },
            set(val) {
                this.$set(this.columns, this.selectedUuid, val);
            },
            cache: false
        },
        nextInOrder() {
            let keys = Object.keys(this.columns);
            if (keys.length > 0) {
                return this.columns[keys[keys.length - 1]].order + 1;
            }
            return 1;
        }
    }

}
</script>

<style scoped>

</style>
