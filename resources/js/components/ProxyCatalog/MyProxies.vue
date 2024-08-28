<template>
    <div class="purchase-info__box purchase-info-box">
        <div class="purchase-info-box__inner">
            <div class="purchase-info-box__icon">
                <img src="../../../images/icons/icon_alarm.svg" alt="alarm">
            </div>
            <h4 class="purchase-info-box__title">
                My proxies
            </h4>
        </div>
        <div
            class="purchase-info-box__inner"
            v-if="proxies.length > 0"
            v-for="proxy in proxies"
            :key="proxy.id"
            @click="copyProxy(proxy.id)"
        >
                <div class="purchase-info-box__icon">
                    <img src="../../../images/icons/icon_copy.svg" alt="copy">
                </div>
                {{ proxy.ip_shown }}:{{ proxy.port }}
        </div>
        <div class="purchase-info-box__inner"
            v-if="socks5Auth.username.length !== 0 && socks5Auth.password.length !== 0"
        >
            <div class="purchase-info-box__icon">
                <img src="../../../images/icons/icon_key.svg" alt="key">
            </div>
            {{socks5Auth.username}}:{{socks5Auth.password}}
        </div>
        <p class="purchase-info-box__text">
            For better security, set <br>
            <router-link to="/user/socks">proxy password</router-link>
        </p>
    </div>

</template>

<script>
import SnackGeneral from "../Snacks/SnackGeneral.vue";
import Socks5Mixin from "../../mixins/Socks5Mixin.vue";
import CatalogService from "../../services/api/CatalogService.js";

export default {
    name: 'MyProxies',
    components: {SnackGeneral},
    mixins: [
        Socks5Mixin
    ],
    data() {
        return {
            snackData: {
                isOpen: false,
                color: '',
                text: ''
            },
            proxies: []
        }
    },
    methods: {
         getMyProxies() {
            (new CatalogService)
                .getMyCatalog()
                .then((res) => {
                    const data = res.data
                    this.proxies = data.proxies.data;
                })
                .catch((err) =>  {
                    console.error(err.response.data);
                });
        },
        copyProxy(proxyId) {
            const proxy = this.proxies.find(proxy => proxy.id === proxyId);
            if(!proxy.is_static || ('' !== this.socks5Auth.username || '' !== this.socks5Auth.password)) {
                const string = proxy.connection;
                this.copyString(string)
                this.$emit('setSnackBarString', `Copied: ${string}`)
            } else {
                this.$emit('setSnackBarString', `Please set up Socks5 credentials`)
            }
        },
    },
    mounted() {
        const socks5Auth = this.$store.getters.socks5Auth;
        if('' !== socks5Auth.username || '' !== socks5Auth.password) {
            this.socks5  = socks5Auth;
        }
        this.getMyProxies();
    }
}
</script>

<style scoped>

</style>
