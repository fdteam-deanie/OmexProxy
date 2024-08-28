<template>
<Modal id="buy-unlimited-modal" ref="modal">
    <template #header>
        <h2>Create new ticket</h2>
        <div v-if="status === 'error'" class="alert badge alert_red">{{ message }}</div>
    </template>

    <p v-if="status === 'success'">
        <img src="../../../images/icons/icon_check.svg" class="img_success" alt=""/>
        <span>  {{ message }}</span>
    </p>

    <div v-if="status !== 'success'">

        <div class=" flex ai_center">
            <p class="mr-6">Proxy: </p>
            <div class="flag" :style="{ backgroundImage: `url(${proxy.flag})` }"></div>
            <span class="mr-6">{{ proxy.ip }}</span>
            <span>{{proxy.location}}</span>
        </div>

        <textarea v-model="ticketText" placeholder="Ticket text" class="form_input margin_top_10 ticket-text"></textarea>

        <div class="popup_end">
            <div class="popup_row">

            </div>
        </div>
    </div>

    <template #footer>
        <button type="button" @click="hide()" class="btn btn--secondary">Close</button>
        <button v-if="status !== 'success'" type="button" @click="complaint()" class="btn btn--blue">Send</button>
    </template>
</Modal>
</template>

<script>
import Modal from "./Modal.vue";
import Form from "vform";
import {authService} from "../../services/api/AuthService.js";

export default {
    name: "ClaimBadProxyModal",
    components: {Modal},
    props: {
    },
    data() {
        return {
            status: null,
            message: null,
            ticketText: null,
            proxy: {
                id: null,
                ip: null,
                location: {
                    country: {
                        flag: null
                    }
                }
            }
        }
    },
    methods: {
        show(proxy) {
            this.init();
            this.proxy = proxy;
            this.$refs.modal.show();
        },
        hide() {
            this.$refs.modal.hide();
        },
        async complaint() {
            await new Form({
                    proxy_id: this.proxy.id,
                    message: this.ticketText
                })
                .post('/api/complaints', {
                    headers: { ...authService.getAuthHeader() }
                })
                .then((response) => {
                    this.status = 'success';
                    this.message = response.data.message;
                })
                .catch((error) => {
                    this.status = 'error';
                    this.message = error.response.data.message;
                });

        },
        init() {
            this.status = null;
            this.message = null;
            this.ticketText = null;
        }
    }
}
</script>

<style scoped>
.mr-6 {
    margin-right: 6px;
}
.ticket-text {
    padding: 7px !important;
    height: 100px;
}
</style>
