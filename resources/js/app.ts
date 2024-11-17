import './bootstrap';
import '../css/app.css';

import {createApp, h, DefineComponent} from 'vue';
import {createInertiaApp, router} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueMatomo from 'vue-matomo';

const appName = import.meta.env.VITE_APP_NAME || 'Vierkandle';

createInertiaApp({
    title: (title) => `${appName} - ${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    // @ts-ignore
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(VueMatomo, {

                host: 'https://analytics.jonathanm.nl/',
                siteId: import.meta.env.VITE_APP_ENV == 'production' ? 1 : 2,
                enableHeartBeatTimer: true,
            })
            .mount(el);
        router.on('navigate', async (event) => {
            const user = event.detail.page.props.auth.user;
            if (user) {
                window._paq.push(['setUserId', user.email]);
            }
            window._paq.push(['setCustomUrl', event.detail.page.url]);

            setTimeout(() => {
                window._paq.push(['trackPageView']);
            }, 1000);
        });

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});

