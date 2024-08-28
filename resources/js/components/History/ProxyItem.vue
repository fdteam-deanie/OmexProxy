<template>
    <div class="proxy-table__row proxy-row">
        <div class="proxy-row__box proxy-row-box">
            <img class="proxy-row-box__img" :src="proxyData.flag" alt="flag">
            <p class="proxy-row-box__text">{{ proxyData.ip }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text" :class="proxyData.is_online ? 'text-green' : ''">
                {{ proxyData.is_online ? 'Yes' : 'No'}}
            </p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text" :class="proxyData.is_paid ? 'text-green' : ''">
                {{ proxyData.is_paid ? 'Yes' : 'Expired'}}
            </p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.location }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.isp }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.type }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.paid_at }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.expired_at }}</p>
        </div>

        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">
                ${{ proxyData.price }}
            </p>
            <div
                class="proxy-row-box__buy"
                v-if="false"
                @click="buy(proxyData.id)"
            >
                <img src="../../../images/icons/icon_buy.svg" alt="buy_icon">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProxyItem",
    props: {
        proxyData: {
            type: Object,
            require: true
        }
    },
    methods: {
        buy(proxyId) {

        }
    }
}
</script>

<style scoped>
    .text-green {
        color: #368c36;
    }

    .proxy-row-box {
        &:nth-child(1) {
            max-width: 100px;
        }

        &:nth-child(2) {
            max-width: 80px;
        }

        &:nth-child(3) {
            max-width: 80px;
        }

        &:nth-child(4) {
            max-width: 205px;
        }

        &:nth-child(5) {
            max-width: 250px;
        }

        &:nth-child(6) {
            max-width: 80px;
        }

        &:nth-child(7) {
            max-width: 100px;
        }

        &:nth-child(8) {
            max-width: 100px;
        }

        &:nth-child(9) {
            max-width: 60px;
        }
    }
</style>
