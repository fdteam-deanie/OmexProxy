<template>
<Modal id="buy-unlimited-modal" ref="modal">
    <template #header>
        <h2>Buy unlimited for a day</h2>
        <div v-if="status === 'error'" class="alert badge alert_red">{{ message }}</div>
    </template>

    <p v-if="status === 'success'">
        <img src="../../../images/icons/icon_check.svg" class="img_success"/>
        <span>  {{ message }}</span>
    </p>

    <div v-if="status !== 'success'">
        <p>Do you really want to buy unlimited access to all proxies for one day?</p>

        <div class="popup_end">
            <div class="popup_row">
                <p>Amount: <span>${{price}}</span></p>
            </div>
    </div>
    </div>
    <template #footer>
        <button type="button" @click="hide()" class="btn btn--secondary">Close</button>
        <button v-if="status !== 'success'" type="button" @click="buy()" class="btn btn--blue">Continue</button>
    </template>
</Modal>
</template>

<script>
import Modal from "./Modal.vue";
import {authService} from "../../services/api/AuthService.js";
import UnlimitedSubscriptionMixin from "../../mixins/UnlimitedSubscriptionMixin.vue";
import UnlimitedSubscriptionService from "../../services/api/UnlimitedSubscriptionService.js";

export default {
    name: "BuyUnlimitedModal",
    mixins: [UnlimitedSubscriptionMixin],
    components: {Modal},
    data() {
        return {
            status: null,
            message: null,
            price: 0
        }
    },
    methods: {
        show() {
            this.init();
            this.$refs.modal.show();
        },
        hide() {
            this.$refs.modal.hide();
        },
        async buy() {
            (new UnlimitedSubscriptionService())
                .subscribeUnlimitedSubscription()
                .then((response) => {
                    this.status = 'success';
                    this.message = response.data.message;
                    this.getActiveUnlimitedSubscription()
                    this.$emit('bought')
                })
                .catch((error) => {
                    this.status = 'error';
                    this.message = error.response.data.message;
                });

        },
        getPrice(){
            (new UnlimitedSubscriptionService())
                .getUnlimitedSubscriptionPrice()
            .then((response) => {
                this.price = response.data.price;
            })
        },
        init() {
            this.status = null;
            this.message = null;
            this.getPrice();
        }
    }
}
</script>

<style scoped>

</style>
