import './bootstrap';
import '../sass/app.scss'

import { createApp } from 'vue';
import Router from '@/router'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);
app.use(Router)
app.use(VueSweetalert2)
app.mount('#app');
