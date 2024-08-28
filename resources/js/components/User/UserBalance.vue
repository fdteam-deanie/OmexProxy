<template>
	<section class="page part_bg">
		<div class="container">
            <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
			<div class="section_part width_70 margin_auto">
				<h2 class="center">
					<span>Make a Deposit</span>
				</h2>
				<br />
				<div v-if="paymentForm.errors.has('amount')" v-html="paymentForm.errors.get('amount')" class="alert alert_red badge" />
				<div v-if="paymentForm.errors.has('wrong')" v-html="paymentForm.errors.get('wrong')" class="alert alert_red badge" />
				<div v-if="success" v-html="successMessage" class="alert badge" />
				<form autocomplete="off" @submit.prevent="topUp" class="form flex flex_column ai_center jc_center">
					<div class="form_group">
						<input v-mask="'###.##'" type="text" v-model="paymentForm.amount" placeholder="1.15" class="form_input" />
					</div>
					<div class="form_group width_auto flex jc_center">
						<button class="btn" :disabled="paymentForm.busy">Continue</button>
					</div>
				</form>
			</div>
		</div>
	</section>
</template>
<script>
    import BalanceMixin from "../../mixins/BalanceMixin.vue";
    import PaymentService from "../../services/api/PaymentService.js";
    import Breadcrumbs from "../Common/Breadcrumbs.vue";

	export default {
		name: 'UserBalance',
        components: {Breadcrumbs},
        mixins: [ BalanceMixin ],
		data() {
			return {
                breadCrumbsData: [
                    { title:'User account', path: '/user/account' },
                    { title:'Balance', path: '/user/balance' }
                ],
				paymentForm: new PaymentService({
					amount: '',
				}),
				success: false,
				successMessage: null,
			}
		},
		mounted() {
			console.log('UserBalance');
		},
		methods: {
			async topUp() {
				await this.paymentForm
                    .topUpBalance()
					.then((res) => {
						if ('success' === res.data.status) {
							this.success = true;
							this.successMessage = res.data.message;
                            this.getBalance();
                            this.paymentForm.amount = ''
						}
					})
					.catch((err) => {
						console.error(err)
					});
			},
		},
        computed: {
            userData() {
                return this.$store.getters.userData
            }
        }
	}
</script>
