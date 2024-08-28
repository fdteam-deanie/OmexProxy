<template>
    <CartModal
        v-if="cartModalShow"
        :orderStatus="orderStatus"
        :orderMessage="orderMessage"
        :orderedProxies="orderedProxies"
        :hasActiveUnlimitedSubscription="hasActiveUnlimitedSubscription"
        @close-modal="cartModalShow = false"
        @delete-from-cart="deleteFromCart"
        @update-cart-item="updateCartItem"
        @confirm-order="confirmOrder"
    />
    <BuyUnlimitedModal ref="buyUnlimitedModal" @bought="boughtUnlimitedSubscription()"/>
    <main class="main">
        <GeoComponent
            :continent-id="selectedContinentId"
            :country-id="selectedCountryId"
            @set-continent-id="setContinentId"
            @set-country-id="setCountryId"
        />
        <section class="main__proxy proxy">
            <div class="big-container">
                <div class="proxy__wrapper">
                    <div class="proxy__inner">
                        <div
                            class="proxy__filters filters"
                            :class="showFilters ? 'filters--active' : ''"
                        >
                            <div class="filters__top filters-top"
                                @click="showFilters = true"
                            >
                                <div class="filters-top__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                         viewBox="0 0 20 20" fill="none">
                                        <path
                                            d="M18.3337 2.5H1.66699L8.33366 10.3833V15.8333L11.667 17.5V10.3833L18.3337 2.5Z"
                                            stroke="#4470FE" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="filters-top__text">
                                    Filters
                                </p>
                                <svg class="filters-top__arrow" xmlns="http://www.w3.org/2000/svg" width="16"
                                     height="16" viewBox="0 0 16 16" fill="none">
                                    <path
                                        d="M8.39967 8L5.33301 4.93333L6.26634 4L10.2663 8L6.26634 12L5.33301 11.0667L8.39967 8Z"
                                        fill="#313131" />
                                </svg>
                            </div>
                            <div class="filters__inner">
                                <form class="filters__form filters-form">
                                    <div class="filters-form__checkbox">
                                        <label for="eup" class="container">
                                            Exclude used proxies
                                            <input id="eup" type="checkbox" v-model="filters.eup">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="filters-form__checkbox">
                                        <label for="ebp" class="container">
                                            Exclude blacklisted proxies
                                            <input id="ebp" type="checkbox" v-model="filters.ebp">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="filters-form__checkbox">
                                        <label for="ebp" class="container">
                                            Residential only proxies
                                            <input id="ebp" type="checkbox" v-model="filters.ebp">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="filters-form__checkbox">
                                        <label class="container">
                                            Enable autorefresh
                                            <input type="checkbox" v-model="autoRefresh">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="filters-form__checkbox">
                                        <label for="mp" class="container">
                                            Mobile proxy
                                            <input id="mp" type="checkbox" v-model="filters.mp">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="filters-form__checkbox">
                                        <label for="sp" class="container">
                                            Server proxies
                                            <input id="sp" type="checkbox" v-model="filters.sp">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <button class="filters-form__button" type="button" @click="resetFilters">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 20 20" fill="none">
                                            <path
                                                d="M5.83325 5.83325L14.1666 14.1666M5.83325 14.1666L14.1666 5.83325"
                                                stroke="#262626" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span>reset filters</span>
                                    </button>
                                    <button
                                        class="filters__hide"
                                        type="button"
                                        @click="showFilters = false"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 20 20" fill="none">
                                            <path
                                                d="M9.4054 10L15 15.3667L13.2973 17L6 10L13.2973 3L15 4.63333L9.4054 10Z"
                                                fill="#BABABA" />
                                        </svg>
                                        <span>hide filters</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="proxy__options options" data-aos="zoom-in">
                            <form class="options__form options-form">

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        IP
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="IP" v-model="filters.ip">
                                </div>

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        Domain
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="DOMAIN" v-model="filters.domain">
                                </div>

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        State
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="STATE" v-model="filters.state">
                                </div>

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        City
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="CITY" v-model="filters.city">
                                </div>

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        ISP
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="ISP" v-model="filters.isp">
                                </div>

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        ZIP
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="ZIP" v-model="filters.zip">
                                </div>

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        Speed
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="Speed" disabled>

                                </div>
                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        Ping
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="ping" disabled>
                                </div>
                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        Type
                                    </h3>
                                    <SelectComponent
                                        :options="proxyTypes"
                                        @selected-value="setTypeToFilter"
                                    />
                                </div>

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        Added
                                    </h3>
                                    <SelectComponent
                                        :options="addedOptions"
                                        @selected-value="setAddedToFilter"
                                    />

                                </div>

                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">
                                        Price
                                    </h3>
                                    <input class="options-form-box__input" type="text" placeholder="Price" v-model="filters.price">
                                </div>
                            </form>
                        </div>
                        <div class="proxy__table proxy-table" data-aos="zoom-in">
                            <ProxyTable
                                :proxies="proxies"
                                :selected-proxy-id="selectedProxyId"
                                @add-to-cart="addToCart"
                                @select-proxy="selectProxy"
                            />
                            <PerPageComponent
                                :values="perPageValues"
                                :selected-value="perPageCnt"
                                :total="total"
                                @setValue="setPerPageCnt"
                            />
                            <PaginationComponent
                                v-if="pages && pages.length > 3"
                                :pages="pages"
                                :page="page"
                                :last-page="lastPage"
                                @setPage="setPage"
                            />
                        </div>
                    </div>
                    <aside class="proxy__purchase purchase" data-aos="zoom-in">
                            <NewProxyDetailed
                                ref="proxyDetail"
                            />

                        <div class="purchase__info purchase-info">
                            <CartSection
                                v-if="loggedIn"
                                :hasActiveUnlimitedSubscription="hasActiveUnlimitedSubscription"
                                :unlimitedSubscription="unlimitedSubscription"
                                :unlimitedSubscriptionPrice="unlimitedSubscriptionPrice"
                                @deleteFromCart="deleteFromCart"
                                @showCartModal="showCartModal"
                                @showBuyUnlimitedModal="showBuyUnlimitedModal"
                            />
                            <MyProxies
                                v-if="loggedIn"
                                @setSnackBarString="openSnackBar"
                            />
                        </div>
                    </aside>
                </div>
            </div>
        </section>
        <SnackGeneral ref="snackbar" />
    </main>
</template>

<script>
import CatalogService from "../services/api/CatalogService.js";
import CartService from "../services/api/CartService.js";
import ProxyService from "../services/api/ProxyService.js";
import UnlimitedSubscriptionService from "../services/api/UnlimitedSubscriptionService.js";

import SelectComponent from "../components/Common/Table/SelectComponent.vue";
import GeoComponent from "../components/ProxyCatalog/Countries.vue";
import ProxyTable from "../components/ProxyCatalog/ProxyTable.vue";
import MyProxies from "../components/ProxyCatalog/MyProxies.vue";
import NewProxyDetailed from "../components/Common/NewProxyDetailed.vue";
import CartSection from "../components/ProxyCatalog/CartSection.vue";
import CartModal from "../components/ProxyCatalog/CartModal.vue";
import SnackGeneral from "../components/Snacks/SnackGeneral.vue";
import BuyUnlimitedModal from "../components/Modals/BuyUnlimitedModal.vue";
import PaginationComponent from "../components/Common/Table/PaginationComponent.vue";
import PerPageComponent from "../components/Common/Table/PerPageComponent.vue";

import Socks5Mixin from "../mixins/Socks5Mixin.vue";
import BalanceMixin from "../mixins/BalanceMixin.vue";

export default {
    name: "ProxyCatalog",
    components: {
        PerPageComponent,
        PaginationComponent,
        BuyUnlimitedModal,
        SnackGeneral,
        CartModal,
        CartSection,
        NewProxyDetailed,
        MyProxies,
        ProxyTable,
        GeoComponent,
        SelectComponent
    },
    mixins: [ Socks5Mixin, BalanceMixin ],
    data() {
        return {
            autoRefresh: false,
            proxies: [],
            selectedContinentId: 4,
            selectedCountryId: null,
            selectedProxyId: null,
            showFilters: true,
            addedOptions: [
                {title: 'Any', value: null, selected: true},
                {title: 'Today', value: 'today', selected: false},
                {title: 'Last 3 days', value: '3', selected: false},
                {title: 'Last week', value: 'week', selected: false},
                {title: 'Last month', value: 'month', selected: false},
            ],
            proxyTypes: [
                {title: 'Any', value: false, selected: true}
            ],
            total: 0,
            filters: {
                eup: false,
                ebp: false,
                rop: false,
                mp: false,
                sp: false,
                ip: "",
                domain: "",
                state: "",
                city: "",
                isp: "",
                zip: "",
                type: null,
                added: null,
                price: "",
            },
            perPageValues: [ 25, 50, 75, 100, 150 ],
            perPageCnt: 25,
            page: 1,
            pages: undefined,
            lastPage: undefined,

            hasActiveUnlimitedSubscription: false,
            unlimitedSubscription: null,
            unlimitedSubscriptionPrice: 0,

            /* Cart */
            cartItems: [],
            cartProxyIds: [],
            cartTotal: 0,
            cartModalShow: false,

            /* Order */
            orderStatus: null,
            orderMessage: null,
            orderedProxies: [],
        }
    },
    methods: {
        setAddedToFilter(value) {
            this.addedOptions.forEach((option) => {
                option.selected = option.value === value;
            });
            this.filters.added = value;
        },
        setTypeToFilter(value) {
            this.proxyTypes.forEach((option) => {
                option.selected = option.value === value;
            });
            this.filters.type = value;
        },
        setContinentId(id){
            this.selectedContinentId = id
        },
        setCountryId(id){
            this.selectedCountryId = id
        },
        getTypes() {
            (new ProxyService())
                .getProxyTypes()
                .then(res => {
                    const types = res.data.types;
                    types.forEach((type) => {
                        this.proxyTypes.push({title: type.name, value: type.id, selected: false})
                    })
                })
                .catch(err => {
                    console.error(err.response.data);
                })
        },
        getProxies() {
            this.selectedProxyId = null;
            const catalogService = new CatalogService();
            catalogService.update({
                filters: this.filters,
                country_id: this.selectedCountryId,
                continent_id: this.selectedContinentId,
                count: this.perPageCnt,
                page: this.page
            });
            catalogService.getCatalog()
                .then( res => {
                    this.proxies = res.data.proxies.data;
                    this.pages = res.data.proxies.links;
                    this.lastPage = res.data.proxies.last_page;
                    this.total = res.data.proxies.total;
                })
                .catch( err => {
                    console.error(err.response.data);
                })

        },
        selectProxy(proxyId) {
            this.selectedProxyId = proxyId;
            document.body.classList.add('cart--active');
        },
        resetFilters() {
            this.filters = {
                eup: false,
                ebp: false,
                rop: false,
                mp: false,
                sp: false,
                ip: "",
                domain: "",
                state: "",
                city: "",
                isp: "",
                zip: "",
                type: [],
                added: null,
                price: null,
            }
        },
        setPage(value) {
            this.page = value;
        },
        setPerPageCnt(value) {
            this.perPageCnt = value;
        },
        loadCart() {
            (new CartService)
                .getCart()
                .then((res) => {
                    this.setCardData(res.data.cartItems, res.data.cartTotal)
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        },
        addToCart(proxyId) {
            this.cartProxyIds.push(proxyId);

            (new CartService())
                .addToCart(proxyId)
                .then(res => {
                    if (res.data.status === 'success') {
                        this.setCardData(res.data.cartItems, res.data.cartTotal)
                        this.getProxies();
                    }
                })
                .catch(err => {
                    console.error(err.response.data)
                })
        },
        setCardData(cartItems, cartTotal) {
            this.cartItems = cartItems;
            this.cartTotal = cartTotal;

            this.$store.commit('setCart', {
                cartItems: this.cartItems,
                cartTotal: parseFloat(this.cartTotal)
            })
        },
        async updateCartItem(proxyId, days) {
            await (new CartService)
                .updateCartItem( proxyId, days )
                .then((res) => {
                    if (res.data.status === 'success') {
                        this.setCardData(res.data.cartItems, res.data.cartTotal)
                        this.getProxies();
                    }

                }).catch((err) => {
                    console.error(err.response.data);
                });

        },
        deleteFromCart(proxyId) {
            (new CartService)
                .removeFromCart( proxyId )
                .then((res) => {
                    this.setCardData(res.data.cartItems, res.data.cartTotal)
                    this.getProxies();
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        },
        confirmOrder(days) {
            (new CartService)
                .confirmOrder(days)
                .then((res) => {
                    this.orderStatus = 'success';
                    this.orderMessage = res.data.message;
                    this.orderedProxies = res.data.orderedProxies;
                    this.setCardData(res.data.cartItems, res.data.cartTotal)
                    this.getBalance();
                })
                .catch((err) => {
                    this.orderStatus = 'error';
                    this.orderMessage = err.response.data.message;
                });

        },
        showCartModal() {
            this.orderedProxies = [];
            this.orderStatus = this.orderMessage = null;
            if('' !== this.socks5Auth.username || '' !== this.socks5Auth.password) {
                this.cartModalShow = true;
            } else {
                this.$refs.snackbar.showSnackbar(`Please set up Socks5 credentials`);
            }
        },
        showBuyUnlimitedModal() {
            this.$refs.buyUnlimitedModal.show()
        },
        getActiveUnlimitedSubscription() {
            (new UnlimitedSubscriptionService())
                .getActiveUnlimitedSubscription()
                .then(res => {
                    this.hasActiveUnlimitedSubscription  = res.data.hasActiveSubscription;
                    this.unlimitedSubscription  = res.data.subscription;
                    this.unlimitedSubscriptionPrice  = res.data.price;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        boughtUnlimitedSubscription() {
            this.getActiveUnlimitedSubscription();
            this.getBalance();
            this.loadCart();
        },
        openSnackBar(string) {
            this.$refs.snackbar.showSnackbar(string)
        },
    },
    beforeMount() {
        this.getProxies();
        this.getTypes();
        this.loadCart()
        this.getActiveUnlimitedSubscription();
        this.autoRefresh = true;
    },
    beforeUnmount() {
        if (this.autoRefresh) {
            clearInterval(this.timerId);
        }
    },
    computed: {

        loggedIn() {
            return this.$store.getters.loggedIn
        }
    },
    watch: {
        autoRefresh(newVal) {
            if(newVal) {
                this.timerId = setInterval(()=>{
                    this.getProxies();
                }, 10000)
            } else {
                clearInterval(this.timerId)
            }
        },
        filters: {
            handler() {
                this.getProxies();
            },
            deep: true
        },
        selectedCountryId() {
            this.getProxies();
        },
        selectedContinentId() {
            this.getProxies();
        },
        selectedProxyId(newVal) {
            this.$refs.proxyDetail.show(newVal);
        },
        perPageCnt() {
            this.page = 1;
            this.getProxies();
        },
        page() {
            this.getProxies();
        }
    }
}
</script>

<style scoped>

</style>
