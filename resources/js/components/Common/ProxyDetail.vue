<template>
    <div class="pro_block">
        <div class="items items-header smaller">
            <slot name="header" :proxy="proxy"></slot>
        </div>
    </div>
    <div v-if="!proxy.id" class="proxy_data">
        <div class="pd_info">
            <div class="alert badge">Select a Proxy for Info</div>
        </div>
    </div>
    <div v-if="proxy.id" class="proxy_data">
        <div class="pd_info">
            <h4>
                <div class="icon icon_world"></div>
                <span class="pi">IP</span>
            </h4>
            <p>
                <span class="flag" :style="{ backgroundImage: `url(${proxy.location.country.flag})` }"></span>
                <span class="pi">{{ proxy.location.ip }}</span>
            </p>
        </div>
        <div class="pd_info">
            <h4>
                <div class="icon icon_location"></div>
                <span class="pi">Location</span>
            </h4>
            <p>
                <span>Country: {{ proxy.location.country.name }}</span>
            </p>
            <p>
                <span>Domain: {{ proxy.location.domain }}</span>
            </p>
            <p>
                <span>ORG: {{ proxy.location.org }}</span>
            </p>
            <p>
                <span>ISP: {{ proxy.location.isp }}</span>
            </p>
            <p v-if="proxy.location.state">
                <span>State: {{ proxy.location.state }}</span>
            </p>
            <p>
                <span>City: {{ proxy.location.city.name }}</span>
            </p>
            <p v-if="proxy.location.zip">
                <span>ZIP: {{ proxy.location.zip }}</span>
            </p>
            <p>
                <span>Zone: Europe</span>
            </p>
        </div>
        <div class="pd_info">
            <h4>
                <div class="icon icon_info"></div>
                <span>Blacklists</span>
            </h4>
            <p>
                <span>Clean (in Spamhaus)</span>
            </p>
        </div>
        <div class="pd_info">
            <h4>
                <div class="icon icon_cog"></div>
                <span>Info</span>
            </h4>
            <p>
                <span>Added: {{ proxy.info.added }}</span>
            </p>
            <p>
                <span>Type: {{ proxy.info.type }}</span>
            </p>
            <p>
                <span>Ping: {{ proxy.info.ping }}</span>
            </p>
            <p>
                <span>Speed: {{ proxy.info.speed }}</span>
            </p>
            <p>
                <span class="flex ai_center">DNS: <span class="flag"></span>{{ proxy.ip }}</span>
            </p>
            <p>
                <span>Usage: {{ proxy.info.usage }}</span>
            </p>
        </div>
    </div>
    <div class="pro_block">
        <div class="items smaller">
            <slot v-if="proxy.id" :proxy="proxy"></slot>
        </div>
    </div>
</template>

<script>
import ProxyService from "../../services/api/ProxyService.js";

export default {
    name: "ProxyDetail",
    data() {
        return {
            proxy: {}
        }
    },
    methods:{
        async show(proxyId) {
            await (new ProxyService)
                .getProxyDetailInfo(proxyId)
                .then((res) => {
                    this.proxy = res.data.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    }
}
</script>

<style scoped>
.items-header
{
    margin-top: 0px !important;
    font-size: 12px !important;
}
</style>
