<template>
    <div class="proxy__options options options__header_only" data-aos="zoom-in">
        <form class="options__form options-form">
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">IP</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Domain</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">State</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">City</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">ISP</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">ZIP</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Speed</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Ping</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Type</h3>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Expired at</h3>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "Header"
}
</script>

<style scoped>
.options-form-box {
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

    .options-form-box__title {
        padding-left: 3px;
    }
}
</style>
