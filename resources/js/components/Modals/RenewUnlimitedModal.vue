<template>
<Modal id="buy-unlimited-modal" ref="modal">
    <template #header>
        <h2>Renew unlimited subscription</h2>
        <div v-if="status == 'error'" class="alert badge alert_red">{{ message }}</div>
    </template>

    <p v-if="status === 'success'">
        <img src="../../../images/icons/icon_check.svg" class="img_success"/>
        <span>  {{ message }}</span>
    </p>

    <div v-if="status !== 'success'">
        <p>Are you sure you want to renew your subscription by one day?</p>

        <div class="popup_end">
            <div class="popup_row">
                <p>Amount: <span>${{price}}</span></p>
            </div>
        </div>
    </div>

    <template #footer>
        <button type="button" @click="hide()" class="btn btn-secondary">Close</button>
        <button v-if="status !== 'success'" type="button" @click="buy()" class="btn btn--blue">Continue</button>
    </template>
</Modal>
</template>

<script>
import Modal from "./Modal.vue";
import BalanceMixin from "../../mixins/BalanceMixin.vue";
import {authService} from "../../services/api/AuthService.js";

export default {
    name: "BuyUnlimitedModal",
    components: {Modal},
    mixins: [BalanceMixin],
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
            await axios
                .post('/api/unlimited-subscriptions/renew', {}, {
                    headers: { ...authService.getAuthHeader() }
                })
                .then((response) => {
                    this.status = 'success';
                    this.message = response.data.message;
                    this.getBalance();
                    this.$emit('renewed')
                })
                .catch((error) => {
                    this.status = 'error';
                    this.message = error.response.data.message;
                });

        },
        async getPrice(){
            await axios
                .get('/api/unlimited-subscriptions/price', {}, {
                    headers: { ...authService.getAuthHeader() }
                })
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
