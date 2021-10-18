export default {
    computed: {
        contents: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit('input', value);
            }
        }
    },
    props: {
        value: {
            required: false,
            type: String,
            default: null
        },
        configuration: {
            required: false,
            type: Object,
            default: function() {
                return {};
            }
        },
        label: {required: false, type: String},
        hint: {required: false, type: String},
        id: {required: true, type: String},
        errors: {required: false, type: Array, default: () => []}
    },
}
