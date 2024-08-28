<template>
    <div class="purchase__info purchase-info">
        <div class="purchase-info-box__inner w-full">
            <slot name="header" :proxy="proxy"></slot>
        </div>
    </div>

    <div class="purchase__inner">
        <button class="purchase__close" type="button" @click="closeDetailsModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M5.83325 5.83325L14.1666 14.1666M5.83325 14.1666L14.1666 5.83325" stroke="#BADBFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <ul class="purchase__list purchase-list" v-if="!proxy.id">
            <li class="purchase-list__item">
                <div class="purchase-list__inner select_proxy_block pd_info">
                    <span class="purchase-list__title purchase-list__title-blue">Select a Proxy for Info</span>
                </div>
            </li>
        </ul>
        <ul class="purchase__list purchase-list" v-if="proxy.id">
        <li class="purchase-list__item">
            <div class="purchase-list__icon">
                <img src="../../../images/icons/icon_world.svg" alt="icon_world">
            </div>
            <div class="purchase-list__inner">
                <h4 class="purchase-list__title">IP</h4>
                <div class="purchase-list__info purchase-list-info">
                    <div class="purchase-list-info__box">
                        <img class="purchase-list-info__box-img" :src="proxy.location.country.flag" alt="flag">
                        <p class="purchase-list-info__box-text">
                            {{ proxy.ip }}
                        </p>
                    </div>
                </div>
            </div>
        </li>
        <li class="purchase-list__item">
            <div class="purchase-list__icon">
                <img src="../../../images/icons/icon_location.svg" alt="icon_location">
            </div>
            <div class="purchase-list__inner">
                <h4 class="purchase-list__title">
                    Location
                </h4>
                <div class="purchase-list__info purchase-list-info">
                    <p class="purchase-list-info__text">
                        Country: <span>{{ proxy.location.country.name }}</span>
                    </p>
                    <p class="purchase-list-info__text">
                        Domain: <span>{{ proxy.location.domain }}</span>
                    </p>
                    <p class="purchase-list-info__text">
                        ORG: <span>{{ proxy.location.org }}</span>
                    </p>
                    <p class="purchase-list-info__text">
                        ISP: <span> {{ proxy.location.isp }}</span>
                    </p>
                    <p class="purchase-list-info__text" v-if="proxy.location.state">
                        State: <span>{{ proxy.location.state }}</span>
                    </p>
                    <p class="purchase-list-info__text">
                        City: <span>{{ proxy.location.city.name }}</span>
                    </p>
                    <p class="purchase-list-info__text" v-if="proxy.location.zip">
                        ZIP: <span>{{ proxy.location.zip }}</span>
                    </p>
                    <p class="purchase-list-info__text">
                        Zone: <span>{{ proxy.location.continent.name }}</span>
                    </p>
                </div>
            </div>
        </li>
        <li class="purchase-list__item">
            <div class="purchase-list__icon">
                <img src="../../../images/icons/icon_info.svg" alt="icon_info">
            </div>
            <div class="purchase-list__inner">
                <h4 class="purchase-list__title">
                    Blacklists
                </h4>
                <div class="purchase-list__info purchase-list-info">
                    <p class="purchase-list-info__text">
                        Clean (in Spamhaus)
                    </p>
                </div>
            </div>
        </li>
        <li class="purchase-list__item purchase-list__item--info">
            <div class="purchase-list__icon">
                <img src="../../../images/icons/icon_cog.svg" alt="icon_cog">
            </div>
            <div class="purchase-list__inner">
                <h4 class="purchase-list__title">
                    Info
                </h4>
                <div class="purchase-list__info purchase-list-info">
                    <p class="purchase-list-info__text">
                        Added: <span>{{ proxy.info.added }}</span>
                    </p>
                    <p class="purchase-list-info__text">
                        Type: <span>{{ proxy.info.type }}</span>
                    </p>
                    <p class="purchase-list-info__text">
                        Ping: <span>{{ proxy.info.ping }}</span>
                    </p>
                    <p class="purchase-list-info__text">
                        Speed: <span>{{ proxy.info.speed }}</span>
                    </p>
                    <div class="purchase-list-info__box">
                        <p class="purchase-list-info__box-text">
                            DNS:
                        </p>
                        <img class="purchase-list-info__box-img" :src="proxy.location.country.flag" alt="flag">
                        <p class="purchase-list-info__box-text">
                            {{ proxy.ip }}
                        </p>
                    </div>
                    <p class="purchase-list-info__text">
                        Usage: <span>{{ proxy.info.usage }}</span>
                    </p>
                </div>
            </div>
        </li>

    </ul>
    </div>

    <div v-if="proxy.id" class="connection-info" @click="copyProxy(proxy)">
        <div class="purchase-info-box__icon">
            <img src="../../../images/icons/icon_copy.svg" alt="copy">
        </div>
        <span>{{ proxy.connection }}</span>
    </div>

    <div class="purchase__info purchase-info">
        <slot v-if="proxy.id" :proxy="proxy"></slot>
    </div>
    <SnackGeneral ref="snackbar" />
</template>

<script>
import ProxyService from "../../services/api/ProxyService.js";
import SnackGeneral from "../Snacks/SnackGeneral.vue";

export default {
name: "NewProxyDetailed",
    components: {SnackGeneral},
    data() {
        return {
            proxy: {}
        }
    },
    methods:{
        async show(proxyId) {
            if(null !== proxyId) {
                await (new ProxyService)
                    .getProxyDetailInfo(proxyId)
                    .then((res) => {
                        this.proxy = res.data.data;
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            } else {
                this.proxy = {}
            }
        },
        closeDetailsModal() {
            document.body.classList.remove('cart--active');
        },
        copyProxy(proxy) {
            const string = `${proxy.connection}`
            this.copyString(string)
            this.$refs.snackbar.showSnackbar(`Copied: ${string}`)
        },
    }
}
</script>

<style scoped>

 .w-full {
     display: block;
 }
</style>
