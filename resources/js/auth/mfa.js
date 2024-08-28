import '../bootstrap';
import { createApp } from 'vue';

import MFA from './MFA.vue';


const app = createApp(MFA);

/* Components */
import VueTheMask from 'vue-the-mask'
import GeneralMixin from "../mixins/GeneralMixin.vue";

/* App */
app.use(VueTheMask);

app.mixin(GeneralMixin)

app.mount('#mfa');
