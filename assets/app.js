import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
import plugin from 'vue-toastify';
import 'vue-toastify/index.css';
import './styles/app.css';

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));

document.addEventListener('vue:before-mount', (event) => {
    const {
        componentName,
        component,
        props,
        app,
    } = event.detail;

    app.use(plugin, {});
});