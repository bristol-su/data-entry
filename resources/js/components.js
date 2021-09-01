import ColumnTypes from './components/settings/ColumnTypesField';
import FieldArray from './components/settings/FieldArray';
import Toolkit from '@bristol-su/frontend-toolkit';
import Vue from 'vue';

Vue.use(Toolkit);

Vue.component('p-data-entry-column-types', ColumnTypes);
Vue.component('p-data-entry-array', FieldArray);


