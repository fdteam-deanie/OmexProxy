<template>
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <router-link to="/" class="logo">
                    <img class="logo__img" src="../../../images/logo.svg" alt="logo">
                </router-link>
                <nav class="header__menu menu">
                    <ul
                        v-if="loggedIn"
                        class="menu__list menu-list"
                    >
                        <li v-if="!userBlock" class="menu-list__item">
                            <router-link to="/user/proxy" class="menu-list__link">
                                <span>Catalog</span>
                            </router-link>
                        </li>
                        <li class="menu-list__item">
                            <router-link to="/news" class="menu-list__link">
                                <span>News</span>
                            </router-link>
                        </li>
                        <li v-if="!userBlock"  class="menu-list__item">
                            <router-link to="/user/my" class="menu-list__link">
                                <span>My Proxies</span>
                            </router-link>
                        </li>
                        <li v-if="!userBlock" class="menu-list__item">
                            <router-link to="/user/history" class="menu-list__link">
                                <span>History</span>
                            </router-link>
                        </li>
                        <li class="menu-list__item">
                            <router-link to="/support" class="menu-list__link">
                                <span>Support</span>
                            </router-link>
                        </li>
                        <li v-if="!userBlock"  class="menu-list__item">
                            <router-link to="/user/payments" class="menu-list__link">
                                <span>Payments</span>
                            </router-link>
                        </li>
                    </ul>
                    <ul
                        v-if="!loggedIn"
                        class="menu__list menu-list"
                    >
                        <li class="menu-list__item">
                            <router-link
                                to="/user/proxy"
                                class="menu-list__link"
                            >
                                <span>Proxy</span>
                            </router-link>
                        </li>
                        <li class="menu-list__item">
                            <router-link
                                to="/user/proxy"
                                class="menu-list__link"
                            >
                                <span>Pricing</span>
                                <span class="menu-list__info">$0.7/GB</span>
                            </router-link>
                        </li>
                        <li class="menu-list__item">
                            <router-link
                                to="/user/proxy"
                                class="menu-list__link"
                            >
                                <span>API</span>
                            </router-link>
                        </li>
                        <li class="menu-list__item">
                            <router-link
                                to="/user/proxy"
                                class="menu-list__link"
                            >
                                <span>Payments</span>
                            </router-link>
                        </li>
                        <li class="menu-list__item">
                            <router-link
                                to="/user/proxy"
                                class="menu-list__link"
                            >
                                <span>Resources</span>
                            </router-link>
                        </li>
                        <li class="menu-list__item">
                            <router-link
                                to="/user/proxy"
                                class="menu-list__link"
                            >
                                <span>Affiliate</span>
                                <span class="menu-list__info">-10% commission</span>
                            </router-link>
                        </li>
                    </ul>
                </nav>
                <div v-if="loggedIn" class="header__box header-box">
                        <router-link to="/user/account" class="header-box__link" >
                            <img src="../../../images/icons/icon_user.svg" alt="icon_user">
                            <span>{{ user.username }}</span>
                        </router-link>
                        <div class="tooltip">
                            <router-link to="/payments/add" class="header-box__link header-box__link--wallet">
                                <img src="../../../images/icons/icon_wallet.svg" alt="icon_wallet">
                                <span>{{ userBalance }}$</span>
                            </router-link>
                            <span class="tooltiptext">
                                <div>
                                    <p>Your credits: {{ userCredits }}$</p>
                                    <p>Bonus credits: {{ userBonusCredits }}$</p>
                                </div>
                            </span>
                        </div>
                </div>
                <div
                    class="header__box header-box"
                    v-else
                >
                    <router-link
                        class="header-box__link"
                        to="/user/login"
                    >
                        <img src="../../../images/icons/icon_user.svg" alt="icon_user">
                        <span>Login</span>
                    </router-link>
                    <router-link
                        class="header-box__link"
                        to="/user/register"
                    >
                        <span>New Account</span>
                    </router-link>
                </div>
                <button class="header__burger burger" @click="toggleBodyClass">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_50_16677)">
                            <path
                                d="M13.3337 11.6668C13.5905 11.667 13.8374 11.7659 14.0232 11.9431C14.2091 12.1203 14.3197 12.3623 14.332 12.6188C14.3443 12.8753 14.2575 13.1267 14.0895 13.321C13.9216 13.5152 13.6853 13.6374 13.4297 13.6622L13.3337 13.6668H2.66699C2.41018 13.6667 2.16326 13.5678 1.9774 13.3906C1.79154 13.2133 1.68098 12.9714 1.66864 12.7149C1.6563 12.4584 1.74312 12.2069 1.91111 12.0127C2.0791 11.8184 2.31538 11.6963 2.57099 11.6715L2.66699 11.6668H13.3337ZM13.3337 7.00016C13.5989 7.00016 13.8532 7.10552 14.0408 7.29306C14.2283 7.48059 14.3337 7.73495 14.3337 8.00016C14.3337 8.26538 14.2283 8.51973 14.0408 8.70727C13.8532 8.89481 13.5989 9.00016 13.3337 9.00016H2.66699C2.40178 9.00016 2.14742 8.89481 1.95989 8.70727C1.77235 8.51973 1.66699 8.26538 1.66699 8.00016C1.66699 7.73495 1.77235 7.48059 1.95989 7.29306C2.14742 7.10552 2.40178 7.00016 2.66699 7.00016H13.3337ZM13.3337 2.3335C13.5989 2.3335 13.8532 2.43885 14.0408 2.62639C14.2283 2.81393 14.3337 3.06828 14.3337 3.3335C14.3337 3.59871 14.2283 3.85307 14.0408 4.0406C13.8532 4.22814 13.5989 4.3335 13.3337 4.3335H2.66699C2.40178 4.3335 2.14742 4.22814 1.95989 4.0406C1.77235 3.85307 1.66699 3.59871 1.66699 3.3335C1.66699 3.06828 1.77235 2.81393 1.95989 2.62639C2.14742 2.43885 2.40178 2.3335 2.66699 2.3335H13.3337Z"
                                fill="#4470FE" />
                        </g>
                        <defs>
                            <clipPath id="clip0_50_16677">
                                <rect width="16" height="16" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                </button>
            </div>
        </div>
    </header>
</template>

<script>
import BalanceMixin from "../../mixins/BalanceMixin.vue";
import Socks5Mixin from "../../mixins/Socks5Mixin.vue";
import UnlimitedSubscriptionMixin from "../../mixins/UnlimitedSubscriptionMixin.vue";

export default {
    name: "AppNewHeader",
    mixins: [
        BalanceMixin,
        Socks5Mixin,
        UnlimitedSubscriptionMixin
    ],
    methods: {
        toggleBodyClass() {
            document.body.classList.toggle("body--active");
        },
    },
    computed:{
        loggedIn() {
            return this.$store.getters.loggedIn;
        },
        user() {
            return this.$store.getters.userData;
        },
        userBlock() {
            return this.$store.getters.userBlock;
        },
    },
    watch: {
        loggedIn(newVal){
            if(newVal) {
                this.getSocks5Creds()
                this.getBalance()
                this.getActiveUnlimitedSubscription()
            }
        }
    }
}
</script>


<style scoped>

</style>
