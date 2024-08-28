<template>
    <div class="py-6 px-1 md:px-2 lg:px-6">
        <div class="mx-auto py-8 max-w-sm flex justify-center">
            <div class="logo_container">
                <router-link to="/" class="logo">
                    <h1>omex</h1>
                </router-link>
            </div></div>
        <div>
            <form class="bg-white shadow rounded-lg p-8 max-w-[25rem] mx-auto" @submit.prevent>
                <h2 class="text-xl text-center font-normal mb-6">Двухфакторная аутентификация</h2>
                <svg class="block mx-auto mb-6" xmlns="http://www.w3.org/2000/svg" width="100" height="2" viewBox="0 0 100 2"><path fill="#D8E3EC" d="M0 0h100v2H0z"></path></svg>
                <span class="block ml-auto text-sm mb-6">
                    Верификационный код отправлен на адрес электронной почты, связанный с вашей учетной записью
                </span>

                <div class="mb-6">
                    <label class="block mb-2 text-sm" for="code">Код аутентификации</label>
                    <input
                        class="form-control form-input form-input-bordered w-full"
                        id="code"
                        type="text"
                        name="code"
                        placeholder="Введите код"
                        v-model="code"
                    >
                    <span v-if="errorMessage" class="block ml-auto text-sm mb-6 text-red-500">
                        {{errorMessage}}
                    </span>
                </div>
                <div class="block mb-3">
                    <button
                        @click="verify"
                        size="lg"
                        align="center"
                        component="button"
                        class="w-full flex justify-center shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 w-full flex justify-center cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 w-full flex justify-center shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 w-full flex justify-center"
                    >
                        <span>
                            Авторизоваться
                        </span>
                    </button>
                </div>
                <div>
                    <div v-if="leftTime > 0">
                        <span class="text-sm">Вы можете повторно отправить код через: {{showTime}}</span>
                    </div>
                    <button
                        @click="resend"
                        v-else
                        size="lg"
                        align="center"
                        component="button"
                        class="w-full flex justify-center shadow relative bg-white hover:bg-gray-50 text-gray-600 w-full flex justify-center cursor-pointer rounded text-sm font-bold focus:outline-none  inline-flex items-center justify-center h-9 px-3 w-full flex justify-center shadow relative w-full flex justify-center"
                    >
                        <span>
                             Отправить код повторно
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
import {authService} from "../services/api/AuthService.js";

export default {
    name: "MFA",
    data() {
        return {
            leftTime: 0,
            code: '',
            errorMessage: '',
        }
    },
    computed: {
        showTime()
        {
            const pad = function(input) {return input < 10 ? "0" + input : input;};
            return [
                pad(Math.floor(this.leftTime % 3600 / 60)),
                pad(Math.floor(this.leftTime % 60)),
            ].join(":")
        }
    },
    methods: {
        async getLeftTime() {
            await axios
                .get('/api/mfa/left-time', {
                    headers: { ...authService.getAuthHeader() }
                })
                .then((res) => {
                    this.leftTime = res.data.leftTime;
                })
                .catch((err) => {

                });

        },
        async resend() {
            await axios
                .post('/api/mfa/regenerate', {
                    headers: { ...authService.getAuthHeader() }
                })
                .then((res) => {
                    this.leftTime = res.data.leftTime;
                })
                .catch((err) => {

                });

        },
        async verify() {
            await axios
                .post('/api/mfa/verify', {
                    code: this.code
                }, {
                    headers: { ...authService.getAuthHeader() }
                })
                .then((res) => {
                    window.location.href = res.data.url;
                })
                .catch((err) => {
                    console.log(err.response.data.errors)
                    this.errorMessage = err.response.data.message;
                });
        },
        timer() {
            setInterval(() => {
                if (this.leftTime > 0) {
                    this.leftTime = this.leftTime - 1;
                }
            }, 1000);
        }
    },
    mounted() {
        this.getLeftTime();
        this.timer();
    }
}
</script>

<style scoped>

</style>
