<template>
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
</template>

<script>
import HistoryService from "../../services/api/HistoryService.js";

import Filters from "./Filters.vue";
import ProxyItem from "./ProxyItem.vue";
import PerPageComponent from "../Common/Table/PerPageComponent.vue";
import PaginationComponent from "../Common/Table/PaginationComponent.vue";
import UnlimitedSubscriptionMixin from "../../mixins/UnlimitedSubscriptionMixin.vue";

export default {
    name: "ProxyTable",
    components: {
        Filters,
        ProxyItem,
        PerPageComponent,
        PaginationComponent,
    },
    data() {
        return {
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
            this.$emit('selectProxy', proxyId)
        }

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
        }
    }
}
</script>

<style scoped>

</style>
