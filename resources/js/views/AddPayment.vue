<template>
  <div class="wrapper">
    <main class="main">
      <Breadcrumbs :bread-crumbs-data="breadCrumbsData" />
      <section class="main__account account">
        <div class="container">
          <div class="account__inner">
            <div class="account__wrapper account__wrapper--active wallets_block">
              <div class="topup_calc wallets_block">
                <div class="topup_calc--header">
                  <h5>Topup Calculator</h5>
                  <b>Course: 1 BTC = $<span class="btc_rate">{{ rates.bitcoin }}</span>, 1 LTC = $<span
                      class="ltc_rate">{{ rates.litecoin }}</span></b>
                </div>
                <div style="display: flex">
                  <div style="width: 33.33333333%; padding: 15px">
                    <div class="form_group">
                      <span>USD</span>
                      <input v-model="calc.usd" type="number" placeholder="USD" class="form_input" />
                    </div>
                  </div>
                  <div style="width: 33.33333333%; padding: 15px">
                    <div class="form_group">
                      <span style="padding-bottom: 2px;">BTC</span>
                      <input v-model="calc.btc" type="number" placeholder="BTC" class="form_input" />
                    </div>
                  </div>
                  <div style="width: 33.33333333%; padding: 15px">
                    <div class="form_group">
                      <span>LTC</span>
                      <input v-model="calc.ltc" type="number" placeholder="LTC" class="form_input" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="account__heading account-heading-nologin">
            <h1 class="account-heading__title center">
              Add Payment
            </h1>
          </div>
          <div v-if="!walletsLoaded" class="spinner"></div>
          <div v-if="walletsLoaded" class="account__inner" data-aos="zoom-out">
            <div class="account__wrapper account__wrapper--active wallets_block">
              <p v-if="success" class="account-box__updated" v-html="successMessage"></p>
              <div v-for="wallet in wallets" :key="wallet.id" class="wallet_section">
                <div class="wallet_section--header">
                  <h5>Your {{ wallet.type.toUpperCase() }} topup address</h5>
                  <a rel="noopener noreferrer" :href="`https://blockchair.com/bitcoin/address/${wallet.address}`">
                    <span>view my transactions online</span>
                  </a>
                </div>
                <div class="wallet_section--block">
                  <div class="purchase-info-box__inner" @click="copyAddress(wallet.id)">
                    <div class="purchase-info-box__icon">
                      <img src="../../images/icons/icon_copy.svg" alt="copy">
                    </div>
                    {{ wallet.address }}
                  </div>
                  <button class="btn btn--blue" style="margin-left: 25px" @click="checkPaid(wallet.type)">
                    Paid
                  </button>
                </div>
              </div>
              <div class="wallet_section">
                <div class="wallet_section--header">
                  <h5>Top up your balance with a card</h5>
                </div>
                <form class="wallet_section--block wallet_section--block--pay" id="payment-form">
                  <input type="number" class="form_input form_input--pay" placeholder="Enter payment amount"
                    v-model="amount" required>
                  <button class="btn btn--blue" @click="submit"
                    style="margin-left: 25px; white-space: nowrap;">Pay</button>
                </form>
              </div>
            </div>
          </div>
          <div class="account__bg account-bg">
            <div class="account-bg__box">
              <img class="account-bg__box-img" src="../../images/account-bg.png" alt="bg">
              <img class="account-bg__box-img" src="../../images/account-bg-figures.png" alt="bg">
            </div>
          </div>
        </div>
      </section>
    </main>
    <SnackGeneral ref="snackbar" />
  </div>

</template>

<script>
import WalletService from "../services/api/WalletService.js";
import SnackGeneral from "../components/Snacks/SnackGeneral.vue";
import Breadcrumbs from "../components/Common/Breadcrumbs.vue";
import Input from "../components/Form/Input.vue";

export default {
  name: "AddPayment",
  components: { Input, Breadcrumbs, SnackGeneral },
  data() {
    return {
      amount: null,
      stripeSessionId: null,
      breadCrumbsData: [
        { title: 'Payments', path: '/user/payments' },
        { title: 'Add Payments', path: '/payments/add' },
      ],
      walletsLoaded: false,
      wallets: [],
      success: false,
      successMessage: null,
      calc: {
        usd: 50,
        btc: 1,
        ltc: 1
      },
      rates: {
        bitcoin: 1,
        litecoin: 1
      },
    }
  },
  methods: {
    async submit() {
      try {
        const form = document.getElementById('payment-form');

        form.addEventListener('submit', function (event) {
          event.preventDefault();
        });

        const res = await (new WalletService).getStripeSession(this.amount, this.$store.getters.getUserId);
        let session = res.data;

        window.location.href = session.url;
      } catch (error) {
        throw error;
      }
    },
    getRates() {
      (new WalletService)
        .getCoinRates()
        .then(res => {
          let rawCourses = res.data
          for (let coin in rawCourses) {
            if (undefined !== rawCourses[coin].usd) {
              this.rates[coin] = rawCourses[coin].usd
            }
          }

          this.calculateCoinValues(this.calc.usd);
        })
        .catch(err => {
          console.error(err)
        })
    },
    getWallets() {
      (new WalletService)
        .getWallets()
        .then(res => {
          this.walletsLoaded = true;
          this.wallets = res.data.wallets;
        })
        .catch(err => {
          console.error(err.response.data)
        })
    },
    checkPaid(coinCode) {
      (new WalletService)
        .recheckBalance(coinCode)
        .then(res => {
          this.success = true;
          this.successMessage = res.data.message;
        })
        .catch(err => {
          console.error(err.response.data)
        })
    },
    copyAddress(walletId) {
      const wallet = this.wallets.find(wallet => wallet.id === walletId);
      const string = wallet.address
      this.copyString(string)
      this.$refs.snackbar.showSnackbar(`Copied: ${string}`)
    },
    calculateCoinValues(val) {
      this.calc.btc = (val / this.rates.bitcoin).toFixed(6);
      this.calc.ltc = (val / this.rates.litecoin).toFixed(6)
    }
  },
  beforeMount() {
    this.getRates();
  },
  mounted() {
    this.getWallets();
  },
  watch: {
    'calc.usd'(newVal) {
      this.calculateCoinValues(newVal);
    },
  }
}
</script>

<style scoped>
.wallets_block {
  margin: 2% 0;
  max-width: 800px;
  display: block;
  font-size: 18px;


  .wallet_section {
    margin: 20px auto;
    width: 650px;

    .wallet_section--header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      gap: 10px;
      padding: 8px 5px;

    }

    .wallet_section--block {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      gap: 5px;
      padding: 8px 5px;

    }
  }
}

.topup_calc {
  font-size: 16px;

  .topup_calc--header {
    margin-left: 18px;
    margin-right: 18px;
  }
}



.form_input {
  width: 100%;
  height: 61px;
  border-radius: 8px;
  border: 1px solid #4470FE;
  background: white;
  padding-left: 18px;
  box-sizing: border-box;
}

.form_input--pay {
  height: 38px;
  width: 100%;
}

.text-block {
  padding: 20px;
  border-radius: 10px;
  background-color: #f5f5f6;
  color: #000000;
  /* Чорний колір тексту */
  display: flex;
}

.text-block .icon {
  width: 20px;
  height: 20px;
  display: block;
  background-repeat: no-repeat;
  background-position: 0 50%;
  background-size: 20px;
  margin-right: 7px;
}

.btn {
  min-width: 140px;
  max-width: 140px;
  padding: 7px;
}

.spinner {
  border: 8px solid rgba(0, 0, 0, 0.1);
  border-top: 8px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
  margin: 20px auto;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>
