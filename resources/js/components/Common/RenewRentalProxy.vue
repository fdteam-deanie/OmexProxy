<template>
  <BaseBuyProxyForm
      :proxy="proxy"
      :loading="loading"
      text="Renew"
      :show-message="showMessage"
      :message="message"
      :message-type="status"
      @submit="renewRental"
  >
  </BaseBuyProxyForm>
</template>

<script>
import BaseBuyProxyForm from "./BaseBuyProxyForm.vue";
import OrderService from "../../services/api/OrderService.js";

export default {
    name: "RenewRentalProxy",
    components: {BaseBuyProxyForm},
    props: {
        proxy: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            loading: false,
            status: null,
            message: null
        }
    },
    computed: {
        showMessage() {
            return this.status !== null;
        },
    },
    methods: {
        async renewRental(proxy, days) {
            this.loading = true;
            await (new OrderService)
                .renewRental(proxy, days)
                .then((res) => {
                    this.status = 'success';
                    this.loading = false;
                    this.message = res.data.message;
                    proxy.is_paid = true;
                    proxy.expired_at = res.data.expired_at;
                    this.$emit('renewed', proxy, days);
                })
                .catch((err) => {
                    console.error(err.response.data);
                    this.status = 'error';
                    this.loading = false;
                    this.message = err.response.data.message;
                });
        }
    }
}
</script>

<style scoped>

</style>
