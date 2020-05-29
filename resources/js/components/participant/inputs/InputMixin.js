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
        isValid: {
            required: false,
            type: Boolean,
            default: null
        }
    },
}