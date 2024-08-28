<template>
    <main class="main">
        <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
        <section class="main__proxy proxy">
            <div class="container">
                <router-link to="/payments/add" class="account-form__button btn btn--blue" style="width: 250px; margin-bottom: 20px">Make a Deposit</router-link>
                <div class="payments__wrapper">
                    <div class="proxy__inner">
                        <div class="proxy__options options options__header_only" data-aos="zoom-in">
                            <form class="options__form options-form">
                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">Date</h3>
                                </div>
                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">Type</h3>
                                </div>
                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">Amount</h3>
                                </div>
                                <div class="options-form__box options-form-box">
                                    <h3 class="options-form-box__title">Status</h3>
                                </div>
                            </form>
                        </div>
                        <div class="proxy__table proxy-table" data-aos="zoom-in">
                            <PaymentItem
                                v-for="payment in payments"
                                :paymentData="payment"
                                :key="payment.id"
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
                </div>
            </div>
        </section>
    </main>
</template>

<script>
    import PaymentService from "../services/api/PaymentService.js";
    import Breadcrumbs from "../components/Common/Breadcrumbs.vue";
    import PaymentItem from "../components/Payments/PaymentItem.vue";
    import PerPageComponent from "../components/Common/Table/PerPageComponent.vue";
    import PaginationComponent from "../components/Common/Table/PaginationComponent.vue";

	export default {
		name: 'MyPayments',
        components: {PaginationComponent, PerPageComponent, PaymentItem, Breadcrumbs},
		data() {
			return {
                breadCrumbsData: [
                    { title:'User account', path: '/user/account' },
                    { title:'Payments', path: '/user/payments' },
                ],
				payments: [],
                perPageValues: [ 25, 50, 75, 100, 150 ],
                perPageCnt: 25,
                page: 1,
                pages: undefined,
                lastPage: undefined,
                total: 0,
			}
		},
		beforeMount() {
			this.getPayments();
			console.log('MyPayments');
		},
		methods: {
			getPayments() {
                const paymentService = new PaymentService();
                paymentService.update({
                    count: this.perPageCnt,
                    page: this.page
                });
                paymentService
                    .getMyPayments()
					.then((res) => {
						this.payments = res.data.payments.data;
                        this.pages = res.data.payments.links;
                        this.lastPage = res.data.payments.last_page;
                        this.total = res.data.payments.total;
					})
					.catch((error) => {
						console.log(error);
					});
			},
            setPerPageCnt(value) {
                this.perPageCnt = value;
            },
            setPage(value) {
                this.page = value;
            },
		},
        watch: {
            perPageCnt() {
                this.page = 1;
                this.getPayments();
            },
            page() {
                this.getPayments();
            },
        },
        computed: {
            loggedIn() {
                return this.$store.getters.loggedIn
            },
        }
	}
</script>

<style scoped>
    .payments__wrapper {
        margin-left: 100px;
        margin-right: 100px;
    }

    .options-form-box {
        max-width: 200px;
    }
</style>
