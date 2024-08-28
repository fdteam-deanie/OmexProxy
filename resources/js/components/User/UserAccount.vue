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
                            <img :src="icon_logout" alt="logout_icon">
                        </div>
                    </a>
                </div>
                <div class="account__tubs" data-aos="fade-down">
                    <span class="account__tubs-button account__tubs-button--active">
                        Change Password
                    </span>
                    <router-link
                        class="account__tubs-button"
                        to="/user/socks"
                    >
                        Change Socks5 login:pass
                    </router-link>
                </div>
                <div class="account__inner" data-aos="zoom-out">
                    <div class="account__wrapper account__wrapper--active">
                        <p class="account-box__error" v-if="Object.keys(form.errors.all()).length > 0" >
                            <div v-for="error in form.errors.all()">
                                {{ error[0] }}
                            </div>
                        </p>
                        <p v-if="success" class="account-box__updated" v-html="successMessage"></p>
                        <form class="account__form account-form" @submit.prevent="change">
                            <div class="account-form__box" :class=" form.current_password.length > 0 ? 'account-form__box--active' : '' ">
                                <label class="account-form__box-label">
                                    Current password
                                </label>
                                <input class="account-form__box-input" v-model="form.current_password" type="password">
                            </div>
                            <div class="account-form__box" :class=" form.password.length > 0 ? 'account-form__box--active' : '' ">
                                <label class="account-form__box-label">
                                    New password
                                </label>
                                <input class="account-form__box-input" v-model="form.password" type="password">
                            </div>
                            <div class="account-form__box" :class=" form.password_confirmation.length > 0 ? 'account-form__box--active' : '' ">
                                <label class="account-form__box-label">
                                    New password repeat
                                </label>
                                <input class="account-form__box-input" v-model="form.password_confirmation" type="password">
                            </div>
                            <button class="account-form__button btn btn--blue" type="submit">
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
    </main>
</template>

<script>
import icon_logout from "../../../images/icons/icon_logout.svg";

import Breadcrumbs from "../Common/Breadcrumbs.vue";
import AuthMixin from "../../mixins/AuthMixin.vue";
import AuthService from "../../services/api/AuthService.js";

export default {
    name: "UserAccount",
    components: { Breadcrumbs },
    mixins: [ AuthMixin ],
    data() {
        return {
            breadCrumbsData: [
                { title:'User account', path: '/user/login' }
            ],
            icon_logout,
            form: new AuthService({
                current_password: '',
                password: '',
                password_confirmation: '',
            }),
            success: false,
            successMessage: '',
        }
    },
    methods: {
        async change() {
                this.success = false;
                this.successMessage = '';
            await this.form
                .changePassword()
                .then((res) => {
                    if (res.data.status === 'success') {
                        this.success = true;
                        this.successMessage = res.data.message;
                    }
                })
                .catch((err) => {
                    console.log(err)
                });
        },
    }
}
</script>

<style scoped>

</style>
