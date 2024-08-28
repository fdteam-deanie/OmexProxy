<script>
import SnackGeneral from "../Snacks/SnackGeneral.vue";
import {authService} from "../../services/api/AuthService.js";

export default {
    name: "CartModal",
    components: { SnackGeneral },
    props: [ 'orderStatus', 'orderMessage', 'orderedProxies', 'hasActiveUnlimitedSubscription'],
    data() {
        return {
            rentPeriods: [],
            cart: {
                items: [],
                total: 0,
            }
        }
    },
    methods: {
        closeCart() {
        	this.$emit('closeModal');
        },
        deleteFromCart(proxyId) {
            this.$emit('deleteFromCart', proxyId);
        },
        updateCartItem(proxyId, days) {
            this.$emit('updateCartItem', proxyId, days);
        },
        confirmOrder() {
            this.$emit('confirmOrder', this.days);
        },
        copyProxy(proxy) {
            const string = `${proxy.connection}`
            this.copyString(string)
            this.$refs.snackbar.showSnackbar(`Copied: ${string}`)
        },
        getRentPeriods() {
            axios.get('/api/rent-periods', {
                headers: { ...authService.getAuthHeader() }
            })
                .then((res) => {
                    this.rentPeriods = res.data.data;
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        },
        getFilteredPeriods(isStatic = true) {
            return isStatic ?
                this.rentPeriods :
                this.rentPeriods.filter(item => item.days === 1);
            // return this.rentPeriods;
        }
    },
    computed: {
        cartItems() {
            return this.$store.getters.cartItems
        },
        cartTotal() {
            return this.$store.getters.cartTotal
        },
        socks5Auth() {
            return this.$store.getters.socks5Auth
        },
        loggedIn() {
            return this.$store.getters.loggedIn
        }
    },
    mounted() {
        this.getRentPeriods();
    }
}
</script>

<template>
    <div id="cartWindow" class="popup">
        <div class="popup_window">
            <div class="popup_header">
                <h2>Your Cart</h2>
                <div v-if="loggedIn.balance < cartTotal" class="alert badge red">
                    Non-sufficient funds. Please <router-link to="/user/balance">refill your account</router-link>
                </div>
                <div v-if="orderStatus === 'error'" class="alert badge red">{{ orderMessage }}</div>
            </div>
            <div v-if="orderStatus === 'success'" class="popup_body">
                <p>
                    <img src="../../../images/icons/icon_check.svg" class="img_success"/>
                    <span>  {{ orderMessage }}</span>
                </p>
            </div>
            <div v-if="cartTotal > 0 && orderStatus !== 'success'" class="popup_body cart_items">
                <div class="items">
                    <ul>
                        <li v-for="item in cartItems">
                            <div class="item_flag" :style="{ backgroundImage: `url(${item.country_data.flag})` }"></div>
                            <div class="item_ip">{{ item.ip }}, {{ item.country_data.name }}, {{ item.city_data.name }}</div>
                            <div v-if="!hasActiveUnlimitedSubscription" class="rent_days_wrap">
                                <select v-model="item.days" class="form_input rent_days_input" @change="updateCartItem(item.id, item.days)">
                                    <option :key="period.id" :value="period.days" v-for="period in getFilteredPeriods(item.is_static)" >
                                        {{ period.name }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="!hasActiveUnlimitedSubscription" class="item_price item_price_wrap">${{ Number(item.price * item.days).toFixed(2) }}</div>
                            <button type="button" @click="deleteFromCart(item.id);" class="remove"></button>
                        </li>
                    </ul>
                </div>
                <div v-if="!hasActiveUnlimitedSubscription" class="popup_end">
                    <div class="popup_row">
                        <div>
                            <p>Amount: <span>${{ cartTotal }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-if="orderedProxies !== undefined && orderedProxies.length > 0"
                class="popup_body cart_items"
            >
                <div class="items">
                    <ul>
                        <li
                            v-for="proxy in orderedProxies"
                            :key="proxy.id"
                            @click="copyProxy(proxy)"
                        >
                            <div class="item_flag" :style="{ backgroundImage: `url(${proxy.location.country.flag})` }"></div>
                            <div class="item_ip">{{ proxy.ip }}, , {{ proxy.location.country.name }}, {{ proxy.location.city.name }}</div>
                            <div class="item_price">
                                <div class="purchase-info-box__icon">
                                    <img src="../../../images/icons/icon_copy.svg" alt="copy">
                                </div>
                                <span>{{ proxy.connection }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="popup_footer">
                <button type="button" @click="closeCart" class="btn btn--secondary">Close</button>
                <button v-if="cartTotal > 0 && orderStatus !== 'success'" type="button" @click="confirmOrder" class="btn btn--blue">Continue</button>
            </div>
        </div>
        <SnackGeneral ref="snackbar" />
    </div>
</template>

<style scoped>

.rent_days_input {
    border-radius: 4px;
    border: 1px solid #d9d9d9;
    text-transform: uppercase;
    height: 30px;
    padding: 0 0 0 10px;
    font-size: 12px;
    color: #797979;
    width: 81px;
}
.rent_days_wrap
{
    width: 90px;
    text-align: start;
    font-size: 16px;
    margin-right: 20px;
}
.amount_wrap
{
    margin-top: 23px;
}
.item_price_wrap
{
    width: 50px;
    text-align: right;
}

.item_price span {
    margin-left: 2px;
    padding-top: 3px;
}
</style>
