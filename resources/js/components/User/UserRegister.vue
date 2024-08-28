<template>
    <main class="main">
        <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
        <section class="main__entrance entrance">
            <div class="container">
                <div class="entrance__inner" data-aos="fade-down">
                    <h1 class="entrance__title">
                        Register
                    </h1>
                    <div class="entrance__text">
                        Have an account?
                        <router-link to="/user/login">Login now</router-link>
                    </div>
                </div>
                <div class="account__inner" data-aos="zoom-out">
                    <div class="account__wrapper account__wrapper--active">
                        <p v-if="success" class="account-box__updated" v-html="successMessage"></p>
                        <form class="account__form account-form" @submit.prevent="register">
                            <Input
                                label="Your Name"
                                field="name"
                                type="text"
                                v-model="form.name"
                                :has-error="form.errors.has('name')"
                                :error-message="form.errors.get('name')"
                            />
                            <Input
                                label="Email"
                                field="email"
                                type="email"
                                v-model="form.email"
                                :has-error="form.errors.has('email')"
                                :error-message="form.errors.get('email')"
                            />
                            <Input
                                label="Username"
                                field="username"
                                type="text"
                                v-model="form.username"
                                :has-error="form.errors.has('username')"
                                :error-message="form.errors.get('username')"
                            />
                            <Input
                                label="Password"
                                field="password"
                                type="password"
                                v-model="form.password"
                                :has-error="form.errors.has('password')"
                                :error-message="form.errors.get('password')"
                            />
                            <Input
                                label="Confirm Password"
                                field="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                :has-error="form.errors.has('password_confirmation')"
                                :error-message="form.errors.get('password_confirmation')"
                            />

                            <div v-if="form.errors.has('wrong')" v-html="form.errors.get('wrong')" class="alert alert_red badge alert-full" />

                            <button class="account-form__button btn btn--blue" type="submit" :disabled="form.busy">
                                Create account
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
    </main>
</template>
<script>
    import AuthService from "../../services/api/AuthService.js";
    import Breadcrumbs from "../Common/Breadcrumbs.vue";
    import Input from "../Form/Input.vue";

	export default {
		name: 'UserLogin',
        components: {Breadcrumbs, Input},
		data() {
			return {
                breadCrumbsData: [
                    { title:'User register', path: '/user/register' },
                ],
				success: false,
				successMessage: false,

				form: new AuthService({
					name: '',
					email: '',
					username: '',
					password: '',
					password_confirmation: '',
				}),

			}
		},
		mounted() {
			console.log('UserRegister');
		},
		methods: {

			async register() {

				this.success = false;
				this.successMessage = false;

				await this.form
					.register()
					.then((res) => {

						if (res.data.status === 'success') {

							this.success = true;
							this.successMessage = res.data.message;

                            setTimeout(()=>{
                                this.$router.push({name: 'UserLogin'});
                            }, 1000)

						}

					})
					.catch((err) => {
						console.error(err.response.data)
					});

			}

		}
	}
</script>
