<template>
    <div class="proxy-table__inner">
        <div class="proxy-table__row proxy-row">
            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ paymentData.date }}</p>
            </div>
            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ paymentData.type }}</p>
            </div>
            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">${{ paymentData.amount }}</p>
            </div>
            <div class="proxy-row__box proxy-row-box">
                <p class="proxy-row-box__text">{{ paymentData.status }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentItem",
    props: {
        paymentData: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped>
.proxy-row-box {
    max-width: 200px;
}

@media (max-width: 850px) {
     .proxy-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
    }
}
</style>
