<template>
	<header class="header">
		<div class="container flex ai_center jc_between">
			<div class="logo_container">
				<router-link to="/" class="logo">
					<h1>omex</h1>
				</router-link>
			</div>
			<nav>
				<ul v-if="loggedIn">
					<li>
						<router-link to="/user/proxy" class="dropdown">
							<span>Catalog</span>
						</router-link>
					</li>
					<li>
						<router-link to="/user/my" class="dropdown">
							<span>My Proxies</span>
						</router-link>
					</li>
                    <li>
                        <router-link to="/user/history" class="dropdown">
                            <span>History</span>
                        </router-link>
                    </li>
					<li>
						<router-link to="/user/payments" class="dropdown">
							<span>Payments</span>
						</router-link>
					</li>
				</ul>
				<ul v-if="!loggedIn">
					<li>
						<router-link to="/user/proxy" class="dropdown">
							<span>Proxy</span>
						</router-link>
					</li>
					<li>
						<router-link to="/user/proxy">
							<span>Pricing</span>
							<span class="badge">$0.7/GB</span>
						</router-link>
					</li>
					<li>
						<router-link to="/user/proxy" class="dropdown">
							<span>API</span>
						</router-link>
					</li>
					<li>
						<router-link to="/user/proxy" class="dropdown">
							<span>Payments</span>
						</router-link>
					</li>
					<li>
						<router-link to="/user/proxy" class="dropdown">
							<span>Resources</span>
						</router-link>
					</li>
					<li>
						<router-link to="/user/proxy">
							<span>Affiliate</span>
							<span class="badge">-10% commission</span>
						</router-link>
					</li>
				</ul>
			</nav>
			<div class="user_area">
				<ul>
					<li v-if="loggedIn">
						<router-link to="/user/account" class="color_black">
							<div class="icon icon_user"></div>
							<span>{{ user.username }}</span>
						</router-link>
					</li>
					<li v-if="loggedIn">
                        <div class="tooltip">
                            <router-link to="/payments/add">
                                <div class="icon icon_wallet"></div>
                                <span>{{ userBalance }}$</span>
                            </router-link>
                            <span class="tooltiptext">
                                <div>
                                    <p>Your credits: {{ userCredits }}$</p>
                                    <p>Bonus credits: {{ userBonusCredits }}$</p>
                                </div>
                            </span>
                        </div>
					</li>
					<li v-if="!loggedIn">
						<router-link to="/user/login">
							<div class="icon icon_user"></div>
							<span>Login</span>
						</router-link>
					</li>
					<li v-if="!loggedIn">
						<router-link to="/user/register" class="btn">
							<span>New Account</span>
						</router-link>
					</li>
				</ul>
			</div>
		</div>
	</header>
	<router-view></router-view>
</template>
<script>
	import Socks5Mixin from "../../mixins/Socks5Mixin.vue";
    import BalanceMixin from "../../mixins/BalanceMixin.vue";
    import UnlimitedSubscriptionMixin from "../../mixins/UnlimitedSubscriptionMixin.vue";

    export default {
        mixins: [
            BalanceMixin,
            Socks5Mixin,
            UnlimitedSubscriptionMixin
        ],
        computed:{
            loggedIn() {
                return this.$store.getters.loggedIn;
            },
            user() {
                return this.$store.getters.userData;
            }
        },
        mounted(){
            console.log('AppHeader');
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
