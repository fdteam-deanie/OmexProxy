import AOS from 'aos';
import 'aos/dist/aos.css'

import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


AOS.init({
    duration: 1e3,
    offset: 0,
    anchorPlacement: "top-bottom"
})
