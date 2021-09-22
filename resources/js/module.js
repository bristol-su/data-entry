import Vue from 'vue';
import Toolkit from '@bristol-su/frontend-toolkit';
import Participant from './components/participant/Participant';
import Admin from './components/admin/Admin';

Vue.use(Toolkit);

let vue = new Vue({
    el: '#data-entry-root',

    components: {
        Participant, Admin
    }
});

