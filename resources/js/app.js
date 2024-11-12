import { createApp } from 'vue';
import axios from 'axios';
import 'bootstrap';

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

import Notifications from '@kyvg/vue3-notification';

import EmployeeApp from './components/EmployeeApp.vue';

const app = createApp(EmployeeApp);
app.config.globalProperties.$axios = axios;
app.use(Notifications);
app.mount('#app');
