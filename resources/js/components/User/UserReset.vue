<template>
    <div class="main">
        <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
        <section class="main__entrance entrance">
            <div class="container">
                <div class="entrance__inner" data-aos="fade-down">
                    <h1 class="entrance__title">
                        Change password
                    </h1>
                </div>
                <div class="account__inner" data-aos="zoom-out">
                    <div class="account__wrapper account__wrapper--active">
                        <p class="account-box__error" v-if="Object.keys(form.errors.all()).length > 0" >
                            <div v-for="error in form.errors.all()">
                                {{ error[0] }}
                            </div>
                        </p>
                        <p v-if="success" class="account-box__updated" v-html="successMessage"></p>
                        <form class="account__form account-form" @submit.prevent="reset" autocomplete="off">
                            <input type="hidden" v-model="form.token" />
                            <div
                                class="account-form__box account-form__box"
                                :class=" form.password.length > 0 ? 'account-form__box--active' : '' "
                            >
                                <label class="account-form__box-label">
                                    New password
                                </label>
                                <input class="account-form__box-input" type="password" v-model="form.password">
                            </div>
                            <div
                                class="account-form__box account-form__box"
                                :class=" form.password_confirmation.length > 0 ? 'account-form__box--active' : '' "
                            >
                                <label class="account-form__box-label">
                                    Confirm password
                                </label>
                                <input class="account-form__box-input" type="password" v-model="form.password_confirmation">
                            </div>
                            <button class="account-form__button btn btn--blue" type="submit" :disabled="form.busy">
                                Change password
                            </button>
                        </form>
                    </div>
                </div>
                <div class="account__bg account-bg">
                    <div class="account-bg__box">
                        <img class="account-bg__box-img" src="../../../images/account-bg.png" alt="bg">
                        <img class="account-bg__box-img" src="../../../images/account-bg-figures.png" alt="bg">
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
	import { useRoute } from 'vue-router';
    import AuthService from "../../services/api/AuthService.js";
    import Breadcrumbs from "../Common/Breadcrumbs.vue";

	export default {
		name: 'UserReset',
        components: {Breadcrumbs},
		data() {
			return {
                breadCrumbsData: [
                    { title:'User reset', path: '/user/recovery' },
                ],
				success: false,
				successMessage: null,

				form: new AuthService({
					token: '',
					password: '',
					password_confirmation: '',
				}),
			}
		},
		mounted() {
			const route = useRoute();
			this.form.token = route.params.token;
			console.log('UserReset');
		},
		props: {
			token: String,
		},
		methods: {
			async reset() {
				await this.form
					.resetPasswordByToken( this.form.token )
					.then((res) => {
						if (res.data.status === 'success') {
							this.success = true;
							this.successMessage = res.data.message;
							this.form.reset();

                            setTimeout(()=>{
                                this.$router.push({name: 'UserLogin'});
                            }, 1500)
						}
					})
					.catch((err) => {
						console.error(err)
					});
			}
		}
	}
</script>
