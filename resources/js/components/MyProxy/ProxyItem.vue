<template>
    <div class="proxy-table__row proxy-row">
        <div class="proxy-row__box proxy-row-box">
            <img class="proxy-row-box__img" :src="proxyData.location.country.flag" alt="flag">
            <p class="proxy-row-box__text">{{ proxyData.ip_shown }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.location.domain }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.location.state }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.location.city.name }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.location.isp }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.location.zip }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.info.speed ?? '-' }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.info.ping ?? '-' }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.info.type }}</p>
        </div>
        <div class="proxy-row__box proxy-row-box">
            <p class="proxy-row-box__text">{{ proxyData.info.expired_at }}</p>
            <div
                class="proxy-row-box__buy"
                @click="refund"
                v-if="isRefunded"
            >
                <img class="icon-refund" src="../../../images/icons/icon-refund.svg" alt="">
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
        refund() {
            this.$emit('refund', this.proxyData.id)
        }
    },
    computed: {
        isRefunded() {
            const paidAt = new Date(this.proxyData.info.paid_at + " UTC");
            const differenceInMinutes = Math.floor((new Date() - paidAt) / (1000 * 60));
            return !this.proxyData.info.is_static &&  differenceInMinutes < 5;
        }
    }
}
</script>

<style scoped>
.proxy-row-box {
    &:nth-child(1) {
        max-width: 107px;
    }

    &:nth-child(2) {
        max-width: 130px;
    }

    &:nth-child(3) {
        max-width: 135px;
    }

    &:nth-child(4) {
        max-width: 100px;
    }

    &:nth-child(5) {
        max-width: 140px;
    }

    &:nth-child(6) {
        max-width: 45px;
    }

    &:nth-child(7) {
        max-width: 38px;
    }

    &:nth-child(8) {
        max-width: 38px;
    }

    &:nth-child(9) {
        max-width: 50px;
    }

    &:nth-child(10) {
        max-width: 102px;
    }
}

.icon-refund {
    width: 15px;
    height: 18px;
}
</style>
