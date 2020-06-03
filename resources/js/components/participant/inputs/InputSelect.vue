<template>
    <v-select
        :options="options"
        v-model="contents"
        :taggable="canAddOptions"
        class="form-control"
        :class="extraClasses"
        style="height: auto;">
        <template v-slot:header>
            <div v-if="canAddOptions" style="opacity: .8">You may add your own options by typing them below</div>
        </template>
    </v-select>
</template>

<script>
    import InputMixin from './InputMixin';
    import 'vue-select/dist/vue-select.css';
    import VSelect from 'vue-select';
    
    export default {
        name: "InputSelect",

        mixins: [InputMixin],

        components: {
            VSelect
        },
        
        data() {
            return {}
        },

        methods: {},

        computed: {
            options() {
                return this.configuration.hasOwnProperty('select_options') ? this.configuration.select_options : [];
            },
            canAddOptions() {
                return this.configuration.hasOwnProperty('add_own') ? this.configuration.add_own : false;
            },
            extraClasses() {
                let classes = [];
                if(this.isValid) {
                    classes.push('is-valid');
                } else if(this.isValid === false) {
                    classes.push('is-invalid');
                }
                return classes;
            }
        }
    }
</script>

<style scoped>

</style>