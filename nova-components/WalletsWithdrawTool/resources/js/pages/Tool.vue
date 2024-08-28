<template>
  <div>
    <Head title="Wallets Withdraw Tool" />

    <Heading class="mb-6">Wallets Withdraw Tool</Heading>

      <div class="grid md:grid-cols-12 gap-6">

          <Card
              :coin-type="'Bitcoin'"
              :balance="balances.btc"
              @withdraw="withdrawBTC"
          />
          <Card
              :coin-type="'Litecoin'"
              :balance="balances.ltc"
              @withdraw="withdrawLTC"
          />
      </div>
      <Modal
          :isOpen="modal.isOpen"
          :modalStatus="modal.status"
          :modalMessage="modal.message"
          @close-modal="closeModal"
      />
  </div>
</template>

<script>
import Card from "../components/Card.vue";
import Modal from "../components/Modal.vue";

export default {
    components: {
        Modal,
        Card
    },
    props: {
        balances: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            modal: {
                status: undefined,
                message: undefined,
                isOpen: false
            }
        }
    },
    methods: {
        closeModal() {
            this.modal.isOpen = false;
            this.modal.status = undefined;
            this.modal.message = undefined;
        },
        withdrawBTC() {
            this.withdraw('btc')
        },
        withdrawLTC() {
            this.withdraw('ltc')
        },

        withdraw(coin) {
            Nova.request().put('/nova-vendor/wallets-withdraw/withdraw/', {
                coin: coin,
                amount: this.balances[coin] - this.balances[coin]*0.05
            })
                .then(res => {
                    const resData = res.data;

                    this.modal.isOpen = true;
                    this.modal.status = resData.status;
                    this.modal.message = resData.message;

                    console.log(res.data)
                })
                .catch(err => {
                    this.modal.isOpen = true
                    this.modal.status = 'error';
                    this.modal.message = err.response.data.message;

                    console.error(err)
                });
        }
    }

}
</script>

<style>
/* Scoped Styles */
</style>
