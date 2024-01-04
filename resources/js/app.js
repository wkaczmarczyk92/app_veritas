import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createPinia } from 'pinia';

// import BalmUI from 'balm-ui'; // Official Google Material Components
// import BalmUIPlus from 'balm-ui-plus'; // BalmJS Team Material Components
// import 'balm-ui-css';

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const pinia = createPinia();

const vuetify = createVuetify({
    components,
    directives,
})

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue, Ziggy)
            .use(vuetify)
            // .use(BalmUI)
            // .use(BalmUIPlus)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
