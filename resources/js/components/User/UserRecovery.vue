<template>
    <main class="main">
        <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
        <section class="main__entrance entrance">
            <div class="container">
                <div class="entrance__inner" data-aos="fade-down">
                    <h1 class="entrance__title">
                        Password Recovery
                    </h1>
                    <div class="entrance__text">
                        Have an account?
                        <router-link to="/user/login">Login now</router-link>
                    </div>
                </div>
                <div class="account__inner" data-aos="zoom-out">
                    <div class="account__wrapper account__wrapper--active">
                        <p v-if="success" class="account-box__updated" v-html="successMessage"></p>
                        <form class="account__form account-form" @submit.prevent="recovery" autocomplete="off">
                            <Input
                                label="Username"
                                field="username"
                                type="text"
                                v-model="form.username"
                                :has-error="form.errors.has('username')"
                                :error-message="form.errors.get('username')"
                            />

                            <div v-if="form.errors.has('wrong')" v-html="form.errors.get('wrong')" class="alert alert_red badge alert-full" />

                            <button class="account-form__button btn btn--blue" :disabled="form.busy">Send me a link</button>

                        </form>
                        <p class="center account__subtext">
                            <router-link to="/user/recovery">Forgot password?</router-link>
                        </p>
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
    </main>
</template>
<script>
    import AuthService from "../../services/api/AuthService.js";
    import Breadcrumbs from "../Common/Breadcrumbs.vue";
    import Input from "../Form/Input.vue";

	export default {
		name: 'UserRecovery',
        components: {Breadcrumbs, Input},
		data() {
			return {
                breadCrumbsData: [
                    { title:'Reset password', path: '/user/recovery' },
                ],
				form: new AuthService({
					username: '',
				}),
                success: false,
                successMessage: undefined

			}
		},
		mounted() {
			console.log('UserRecovery');
		},
		methods: {

			async recovery() {
				await this.form
					.recovery()
					.then((res) => {
						if (res.data.status === 'success') {
							this.success = true;
							this.successMessage = res.data.message;
						}
					})
					.catch((err) =>  {
						console.error(err);
					});

			}

		}
	}
</script>
