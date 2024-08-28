<template>
    <main class="main">
        <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
        <section class="main__account account">
            <div class="container">
                <div class="account__heading account-heading" data-aos="fade-down">
                    <h1 class="account-heading__title">
                        User account
                    </h1>
                    <a class="account-heading__link" href="javascript:void(null)" @click="logout">
                        <span>
                            Log out
                        </span>
                        <div class="account-heading__link-icon">
                            <img :src="icon_logout" alt="">
                        </div>
                    </a>
                </div>
                <div class="account__tubs" data-aos="fade-down">
                    <router-link
                        class="account__tubs-button"
                        to="/user/account"
                    >
                        Change Password
                    </router-link>
                    <span class="account__tubs-button account__tubs-button--active">
                        Change Socks5 login:pass
                    </span>
                </div>
                <div class="account__inner" data-aos="zoom-out">
                    <div class="account__wrapper account__wrapper--active">
                        <p class="account-box__error" v-if="Object.keys(form.errors.all()).length > 0" >
                            <div v-for="error in form.errors.all()">
                                {{ error[0] }}
                            </div>
                        </p>
                        <p v-if="success" class="account-box__updated">
                            Socks login/port successfully updated.
                        </p>
                        <form class="account__form account-form" @submit.prevent="change">

                            <div
                                class="account-form__box account-form__box"
                                :class=" form.username.length > 0 ? 'account-form__box--active' : '' "
                            >
                                <label class="account-form__box-label">
                                    Login
                                </label>
                                <input class="account-form__box-input" type="text" v-model="form.username">
                            </div>

                            <div
                                class="account-form__box account-form__box"
                                :class=" form.password.length > 0 ? 'account-form__box--active' : '' "
                            >
                                <label class="account-form__box-label">
                                    Password
                                </label>
                                <input class="account-form__box-input" type="text" v-model="form.password">
                            </div>

                            <button class="account-form__button btn btn--blue" type="submit">
                                Change password
                            </button>
                        </form>

                    </div>
                    <p class="account__subtext">
                        It is more safe to use login/pass for proxy port. Without login/password your proxy port
                        might be changed. Read more in <a href="#">Terms</a>. Changes in credentials will affect
                        proxies from
                        next purchases and will not affect proxies you purchased before. Socks Login and/or
                        Password should be 5-7 symbols lenght, only letters (a-Z) and numbers (0-9)
                    </p>
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
import icon_logout from "../../../images/icons/icon_logout.svg";

import Breadcrumbs from "../Common/Breadcrumbs.vue";
import AuthMixin from "../../mixins/AuthMixin.vue";
import UserService from "../../services/api/UserService.js";

export default {
    name: "UserSocks",
    components: {Breadcrumbs},
    mixins: [ AuthMixin ],
    data() {
        return {
            icon_logout,

            breadCrumbsData: [
                { title:'User account', path: '/user/account' },
                { title:'Change Socks Password', path: '/user/socks' }
            ],

            form: new UserService({
                username: '',
                password: '',
            }),

            success: false,
            successMessage: '',
        }
    },
    methods: {

        async change() {

            await this.form
                .setSocks5Credentials()
                .then((response) => {

                    if (response.data.status === 'success') {
                        this.success = true;
                        this.successMessage = response.data.message;
                        this.$store.commit('setSocks5Auth', {
                            username: this.form.username,
                            password: this.form.password
                        })
                    }

                })
                .catch((response) => {
                    console.error(response)
                });

        }

    },
    mounted() {
        if('' !== this.socks5Auth.username || '' !== this.socks5Auth.password) {
            this.form.username = this.socks5Auth.username;
            this.form.password = this.socks5Auth.password;
        }
    },
    watch: {
        socks5Auth(newVal) {
            if('' !== newVal.username || '' !== newVal.password) {
                this.form.username = newVal.username;
                this.form.password = newVal.password;
            }
        }
    },
    computed: {
        socks5Auth() {
            return this.$store.getters.socks5Auth;
        }
    }
}
</script>

<style scoped>

</style>
