<template>
    <BuyUnlimitedModal ref="buyUnlimitedModal" />
    <RenewUnlimitedModal ref="renewUnlimitedModal" @renewed="getActiveUnlimitedSubscription()"/>
    <ClaimBadProxyModal ref="claimBadProxyModal" />
    <main class="main">
        <section class="main__proxy proxy">
            <div class="container">
                <div class="proxy__wrapper">
                    <div class="proxy__inner">
                        <Filters
                            data-aos="zoom-in"
                            :filters="filters"
                            @setToFilter="setToFilter"
                        />
                        <div class="proxy__table proxy-table" data-aos="zoom-in">
                            <div class="proxy-table__inner">
                                <ProxyItem
                                    :class="proxy.id === selectedProxyId ? 'proxy-row--active' : ''"
                                    v-for="proxy in proxies"
                                    :key="proxy.id"
                                    :proxy-data="proxy"
                                    @click="setSelectedProxy(proxy.id)"
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
                    <aside class="proxy__purchase purchase" style="margin-top: 20px" data-aos="zoom-in">
                        <NewProxyDetailed ref="proxyDetail">
                            <template #header>
                                <button v-if="!hasActiveUnlimitedSubscription" type="button" @click="showBuyUnlimitedModal()"  class="account-form__button btn btn--blue w-full">Buy unlimited ${{unlimitedSubscriptionPrice}}</button>
                                <div v-if="hasActiveUnlimitedSubscription" class="message">
                                    <h2>You have active unlimited subscription</h2>
                                    <p>Expired at: {{unlimitedSubscription?.expired_at}}</p>
                                </div>
                                <button v-if="hasActiveUnlimitedSubscription" type="button" @click="showRenewUnlimitedModal()"  class="account-form__button btn btn--blue w-full">Renew subscription ${{unlimitedSubscriptionPrice}}</button>
                            </template>
                            <template #default>
                                <QuickBuyProxy
                                    v-if="selectedProxy.is_online && !selectedProxy.is_paid"
                                    :proxy="selectedProxy"
                                    @bought="bought"
                                />
                                <RenewRentalProxy
                                    v-if="selectedProxy.is_paid && !hasActiveUnlimitedSubscription"
                                    :proxy="selectedProxy"
                                    @renewed="renewedRental"
                                />
                                <button v-if="selectedProxy.is_paid" type="button" @click="complain(selectedProxy)" class="account-form__button btn btn--secondary w-full">
                                    <span>Claim bad proxy</span>
                                </button>
                            </template>
                        </NewProxyDetailed>
                    </aside>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import NewProxyDetailed from "../components/Common/NewProxyDetailed.vue";
import RenewRentalProxy from "../components/Common/RenewRentalProxy.vue";
import HistoryService from "../services/api/HistoryService.js";
import PaginationComponent from "../components/Common/Table/PaginationComponent.vue";
import ProxyItem from "../components/History/ProxyItem.vue";
import PerPageComponent from "../components/Common/Table/PerPageComponent.vue";
import Filters from "../components/History/Filters.vue";
import UnlimitedSubscriptionMixin from "../mixins/UnlimitedSubscriptionMixin.vue";
import QuickBuyProxy from "../components/Common/QuickBuyProxy.vue";
import RenewUnlimitedModal from "../components/Modals/RenewUnlimitedModal.vue";
import BuyUnlimitedModal from "../components/Modals/BuyUnlimitedModal.vue";
import ClaimBadProxyModal from "../components/Modals/ClaimBadProxyModal.vue";
export default {
    name: "History",
    components: {
        ClaimBadProxyModal,
        BuyUnlimitedModal, RenewUnlimitedModal,
        Filters, PerPageComponent, ProxyItem, PaginationComponent,
        RenewRentalProxy, NewProxyDetailed,
        QuickBuyProxy
    },
    mixins: [ UnlimitedSubscriptionMixin ],

    data() {
        return {
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
            filters: {
                ip: null,
                is_online: null,
                is_paid: null,
                country: null,
                state: null,
                city: null,
                zip: null,
                isp: null,
                type: null,
                price: null,
            },
            perPageValues: [ 25, 50, 75, 100, 150 ],
            perPageCnt: 25,
            total: 0,
            page: 1,
            pages: undefined,
            lastPage: undefined,
        }
    },

    methods: {
        getProxies() {
            this.selectedProxyId = null;
            const historyService = new HistoryService();
            historyService.getProxies({
                filters: this.filters,
                count: this.perPageCnt,
                page: this.page
            })
                .then((res) => {
                    const proxyData = res.data.proxies
                    this.proxies = proxyData.data;
                    this.pages = proxyData.links;
                    this.lastPage = proxyData.last_page;
                    this.total = proxyData.total;
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        },
        setToFilter(item) {
            this.filters[item.filter] = item.value;
        },
        setPerPageCnt(value) {
            this.perPageCnt = value;
        },
        setPage(value) {
            this.page = value;
        },
        setSelectedProxy(proxyId) {
            this.selectedProxyId = proxyId;
        },
        showBuyUnlimitedModal() {
            this.$refs.buyUnlimitedModal.show()
        },
        showRenewUnlimitedModal() {
            this.$refs.renewUnlimitedModal.show()
        },
        selectProxy(proxyId) {
            this.selectedProxyId = proxyId;
        },
        bought(proxy, days) {
            this.getBalance();
            this.getProxies();
        },
        renewedRental(proxy, days) {
            this.getBalance();
            this.getProxies();
        },
        async complain(proxy) {
            this.$refs.claimBadProxyModal.show(proxy);
        },
    },
    beforeMount() {
        this.getProxies();
    },
    watch: {
        filters: {
            handler() {
                this.getProxies();
            },
            deep: true
        },
        perPageCnt() {
            this.page = 1;
            this.getProxies();
        },
        page() {
            this.getProxies();
        },
        selectedProxyId(newVal) {
            this.$refs.proxyDetail.show(newVal);
        },
    },
    computed: {
        selectedProxy() {
            const proxy =  this.proxies.find(proxy => proxy.id === this.selectedProxyId);
            return undefined !== proxy ? proxy : this.defaultProxy;
        }
    }
}
</script>

<style scoped>
.message {
    display: block;
    margin-bottom: 10px;
}
</style>
