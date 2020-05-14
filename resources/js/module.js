import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import http from 'http-client';
import AWN from "awesome-notifications";

import ExampleComponent from './components/ExampleComponent';

Vue.prototype.$http = http;
Vue.prototype.$notify = new AWN({position: 'top-right'});
Vue.use(BootstrapVue);

let vue = new Vue({
    el: '#data-entry-root',
    
    components: {
        ExampleComponent
    }
});