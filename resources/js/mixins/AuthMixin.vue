<script>
    import { authService } from "../services/api/AuthService.js";

    export default {
        name: "Auth",
        data() {
            return {
                authTokenName: 'at',
                defaultUserAuthData:{
                    id: undefined,
                    username: '',
                    token: '',
                    loggedIn: false,
                }
            }
        },
        computed: {
            isUserLoggedIn() {
                return this.$store.getters.isLogged;
            }
        },
        methods: {
            checkAuth() {

                if(this.isUserLoggedIn) {
                    return true;
                }

                const access_token = this.getAuthToken();

                if(access_token) {
                    authService.check(access_token)
                        .then(res => {
                            const user = res.data;
                            this.setAuthToken(access_token);
                            this.initLogin({access_token, user});
                        })
                        .catch(err => {
                            console.log(err)
                            this.authFailRouteHandle();
                        });
                } else {
                    this.authFailRouteHandle();
                    this.setAuthToken('')
                }
            },
            initLogin(userData) {
                const {access_token, user} = userData;
                console.log(user)
                this.setAuthToken(access_token);
                this.$store.commit('setLoggedIn', true);
                this.$store.commit('setUserData', user)
            },
            getAuthToken(){
                return authService.getAuthToken();
            },
            setAuthToken(token){
                localStorage.setItem(this.authTokenName, token);
            },
            authFailRouteHandle(){
            },
            logout() {
                authService.logout()
                    .then(() => {
                        this.$store.commit('setUserData', this.defaultUserAuthData);
                        this.$store.commit('setUserBalance', 0);
                        this.$store.commit('setLoggedIn', false);
                        this.$store.commit('setSocks5Auth', {
                            username: '',
                            password: ''
                        });
                        this.setAuthToken('');
                        this.$router.push({name: 'UserLogin'})
                    })
                    .catch((err) => {
                        console.error(err.response);
                    });
            },

        }
    }
</script>

<template>

</template>

<style scoped>

</style>
