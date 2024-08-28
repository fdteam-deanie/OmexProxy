<template>
    <div class="proxy-table__inner">
        <div
            class="proxy-table__row proxy-row"
            :class="proxy.id === selectedProxyId ? 'proxy-row--active' : ''"
            v-for="proxy in proxies"
            :key="proxy.id"
            @click="setSelectedProxy(proxy.id)"
        >
            <div class="proxy-row__box proxy-row-box">
                <img class="proxy-row-box__img" :src="proxy.location.country.flag" alt="flag">
                <p class="proxy-row-box__text">{{ proxy.location.ip }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.location.domain }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.location.state }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.location.city.name }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.location.isp }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.location.zip }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.info.speed ?? '-' }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.info.ping ?? '-' }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.info.type }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ proxy.info.added }}</p>
            </div>

            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">
                    &dollar;{{ proxy.price }}
                </p>
                <div
                    class="proxy-row-box__buy"
                    @click="addToCart(proxy.id)"
                >
                    <img src="../../../images/icons/icon_buy.svg" alt="">
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "ProxyTable",
    props: {
        proxies: {
            type: Array,
            required: true
        },
        selectedProxyId: {
            required: true
        }
    },
    methods: {
        addToCart(proxyId) {
            this.$emit('addToCart', proxyId)
        },
        setSelectedProxy(proxyId) {
            this.$emit('selectProxy', proxyId)
        }
    }
}
</script>

<style scoped>
.proxy-row-box__text {
    margin-left: 10px
}
</style>
