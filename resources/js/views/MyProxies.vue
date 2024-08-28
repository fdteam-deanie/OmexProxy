<template>
    <BuyUnlimitedModal ref="buyUnlimitedModal" />
    <RenewUnlimitedModal ref="renewUnlimitedModal" @renewed="getActiveUnlimitedSubscription()"/>
    <main class="main">
        <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
        <section class="main__proxy proxy">
            <div class="container">
                <div class="proxy__wrapper">
                    <div class="proxy__inner">
                        <Header/>
                        <div class="proxy__table proxy-table" data-aos="zoom-in">
                            <div class="proxy-table__inner">
                                <ProxyItem
                                    :class="proxy.id === selectedProxyId ? 'proxy-row--active' : ''"
                                    v-for="proxy in proxies"
                                    :proxy-data="proxy"
                                    @click="setSelectedProxy(proxy.id)"
                                    @refund="refundProxy"
                                />
                            </div>
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
                        <NewProxyDetailed ref="proxyDetail">
                            <template #header>
                                <button v-if="!hasActiveUnlimitedSubscription" type="button" @click="showBuyUnlimitedModal()"  class="account-form__button btn btn--blue w-full">Buy unlimited ${{unlimitedSubscriptionPrice}}</button>
                                <div v-if="hasActiveUnlimitedSubscription">
                                    <h2>You have active unlimited subscription</h2>
                                    <p>Expired at: {{unlimitedSubscription?.expired_at}}</p>
                                </div>
                                <button v-if="hasActiveUnlimitedSubscription" type="button" @click="showRenewUnlimitedModal()"  class="account-form__button btn btn--blue w-full">Renew subscription ${{unlimitedSubscriptionPrice}}</button>
                            </template>
                            <template #default>
                                <RenewRentalProxy
                                    v-if="!hasActiveUnlimitedSubscription"
                                    :proxy="selectedProxy"
                                    @renewed="renewedRental"
                                />
                            </template>
                        </NewProxyDetailed>

                    </aside>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import Breadcrumbs from "../components/Common/Breadcrumbs.vue";
import NewProxyDetailed from "../components/Common/NewProxyDetailed.vue";
import RenewRentalProxy from "../components/Common/RenewRentalProxy.vue";
import BuyUnlimitedModal from "../components/Modals/BuyUnlimitedModal.vue";
import RenewUnlimitedModal from "../components/Modals/RenewUnlimitedModal.vue";
import Header from "../components/MyProxy/Header.vue";
import PaginationComponent from "../components/Common/Table/PaginationComponent.vue";
import ProxyItem from "../components/MyProxy/ProxyItem.vue";
import PerPageComponent from "../components/Common/Table/PerPageComponent.vue";

import UnlimitedSubscriptionMixin from "../mixins/UnlimitedSubscriptionMixin.vue";
import BalanceMixin from "../mixins/BalanceMixin.vue";

import CatalogService from "../services/api/CatalogService.js";
import ProxyService from "../services/api/ProxyService.js";

export default {
    name: "MyProxies",
    components: {
        PerPageComponent, ProxyItem, PaginationComponent, Header,
        RenewUnlimitedModal,
        BuyUnlimitedModal,
        RenewRentalProxy,
        NewProxyDetailed,
        Breadcrumbs
    },
    mixins: [UnlimitedSubscriptionMixin, BalanceMixin],
    data() {
        return {
            breadCrumbsData: [
                { title:'User account', path: '/user/account' },
                { title:'My Proxies', path: '/user/my' },
            ],
            defaultProxy: {
                id: null,
                location: {
                    country: {
                        id: null,
                        country: null,
                        flag: null,
                    },
                    city: {
                        id: null,
                        name: null
                    },
                    domain: null,
                    org: null,
                    isp: null,
                    state: null,

                },
                blacklist: {
                    status: 'clear',
                },
                info: {
                    added: null,
                    type: null,
                    ping: null,
                    speed: null,
                    dns: null,
                    usage: null,
                },
                price: null,
            },
            selectedProxyId: null,
            proxies: [],
            perPageValues: [ 25, 50, 75, 100, 150 ],
            perPageCnt: 25,
            page: 1,
            pages: undefined,
            lastPage: undefined,
            total: 0,
        }
    },
    methods: {
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
            catalogService.getMyCatalog()
                .then(res => {
                    this.proxies = res.data.proxies.data;
                    this.pages = res.data.proxies.links;
                    this.lastPage = res.data.proxies.last_page;
                    this.total = res.data.proxies.total;
                })
                .catch(err => {
                    console.error(err.response.data);
                })
        },
        setPerPageCnt(value) {
            this.perPageCnt = value;
        },
        setPage(value) {
            this.page = value;
        },
        setSelectedProxy(proxyId) {
            this.selectedProxyId = proxyId;
            document.body.classList.add('cart--active');
        },

        showBuyUnlimitedModal() {
            this.$refs.buyUnlimitedModal.show()
        },
        showRenewUnlimitedModal() {
            this.$refs.renewUnlimitedModal.show()
        },
        refundProxy(proxyId) {
            (new ProxyService())
                .refundProxy(proxyId)
                .then(res => {
                    const data = res.data;
                    if (data.status === 'success') {
                        this.getBalance();
                        this.getProxies();
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(err => {
                    console.error(err)
                })
        },
        renewedRental(proxy, days) {
            this.getBalance();
        },
        selectProxy(proxyId) {
            this.selectedProxyId = proxyId;
        },
    },
    beforeMount() {
        this.getProxies();
    },
    watch: {
        selectedProxyId(newVal) {
            this.$refs.proxyDetail.show(newVal);
        },
        perPageCnt() {
            this.page = 1;
            this.getProxies();
        },
        page() {
            this.getProxies();
        },
    },
    computed: {
        loggedIn() {
            return this.$store.getters.loggedIn
        },
        selectedProxy() {
            const proxy =  this.proxies.find(proxy => proxy.id === this.selectedProxyId);
            return undefined !== proxy ? proxy : this.defaultProxy;
        }
    }
}
</script>

<style scoped>

</style>
