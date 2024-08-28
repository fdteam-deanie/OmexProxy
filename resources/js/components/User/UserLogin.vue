<template>
    <main class="main">
        <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
        <section class="main__entrance entrance" >
            <div class="container">
                <div class="entrance__inner" data-aos="fade-down">
                    <h1 class="entrance__title">
                        Log in
                    </h1>
                    <div class="entrance__text">
                        New to Omex?
                        <router-link to="/user/register">Create an account</router-link>
                    </div>
                </div>
                <div class="account__inner" data-aos="zoom-out">
                    <div class="account__wrapper account__wrapper--active">
                        <form class="account__form account-form" @submit.prevent="login">
                            <Input
                                label="Username"
                                field="username"
                                type="text"
                                v-model="loginForm.username"
                                :has-error="loginForm.errors.has('username')"
                                :error-message="loginForm.errors.get('username')"
                            />
                            <Input
                                label="Password"
                                field="password"
                                type="password"
                                v-model="loginForm.password"
                                :has-error="loginForm.errors.has('password')"
                                :error-message="loginForm.errors.get('password')"
                            />
                            <div v-if="loginForm.errors.has('wrong')" v-html="loginForm.errors.get('wrong')" class="alert alert_red badge alert-full" />

                            <button class="account-form__button btn btn--blue" type="submit">
                                Login
                            </button>
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
	import AuthServiceForm from "../../services/api/AuthService.js";
    import AuthMixin from "../../mixins/AuthMixin.vue";
    import Breadcrumbs from "../Common/Breadcrumbs.vue";
    import Input from "../Form/Input.vue";

    export default {
		name: 'UserLogin',
        components: {Breadcrumbs, Input},
        mixins: [ AuthMixin ],
		data() {
			return {
                breadCrumbsData: [
                    { title:'User login', path: '/user/account' }
                ],
                loginForm: new AuthServiceForm({
                    username: '',
                    password: '',
                })

			}
		},
		mounted() {
			console.log('UserLogin');
		},
		methods: {
            async login() {
                await this.loginForm.login()
                    .then(res => {
                        this.initLogin(res.data.success);
                        this.$router.push({ name: 'Home' })
                    })
                    .catch(err=>{
                        console.error(err);
                    })
            }

		}
	}
</script>
