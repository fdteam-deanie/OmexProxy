import './bootstrap';
import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

import { createApp } from 'vue';

import App from './App.vue';
import router from './router'
import store from "./store";


const app = createApp(App);

router.beforeEach((to, from, next) => {

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

    if (!to.meta.middleware) {
        next()
    }
    else {
        const middleware = to.meta.middleware;

        const context = {
            to,
            from,
            next,
            store,
            router
        };

        return middleware(context)
    }

});

/* Components */
// import AppHeader from './components/Common/AppHeader.vue';
// import AppFooter from './components/Common/AppFooter.vue';
import VueTheMask from 'vue-the-mask'
import GeneralMixin from "./mixins/GeneralMixin.vue";

// app.component('AppHeader', AppHeader);
// app.component('AppFooter', AppFooter);

/* App */
app.use(router);
app.use(store);
app.use(VueTheMask);

app.mixin(GeneralMixin)

app.mount('#app');
