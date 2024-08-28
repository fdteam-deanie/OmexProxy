<template>
    <div class="wrapper">
        <main class="main">
            <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
            <section class="main__support-tickets support-tickets">
                <div class="container">
                    <div class="support-tickets__inner">
                        <div class="support-tickets__heading heading">
                            <h3 class="heading__title" data-aos="fade-down">
                                {{ticket.subject}}
                            </h3>
                        </div>
                        <div class="msger-chat" ref="chatBox">
                            <template
                                v-for="(messages, key) in ticket.messages"
                            >
                                <p class="msger-date">{{key}}</p>
                                <div
                                    v-for="(message, index) in messages"
                                    class="msg"
                                    :class="{
                                        'right-msg': message.is_me,
                                        'left-msg': !message.is_me
                                    }"
                                >
                                    <div class="msg-bubble">
                                        <div class="msg-info">
                                            <div class="msg-info-name">{{message.from}}</div>
                                            <div class="msg-info-time">{{message.time}}</div>
                                        </div>

                                        <div class="msg-text">
                                            <img v-if="message.is_image" :src="'/storage/'+message.body" alt="">
                                            <span v-else>{{message.body}}</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <p v-if="ticket.is_closed" class="msger-date">Ticket closed</p>
                        </div>

                        <form class="msger-inputarea">
                            <textarea
                                id="support-ticket-message"
                                v-model="message"
                                class="msger-input"
                                placeholder=""
                                @input="resize()"
                                rows="1"
                                :disabled="ticket.is_closed"
                                ref="textarea"
                            >
                            </textarea>
                            <button
                                type="button"
                                :disabled="ticket.is_closed"
                                class="msger-send-btn"
                                @click="sendMessage()"
                            >
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
import Breadcrumbs from "../../components/Common/Breadcrumbs.vue";
import SupportService from "../../services/api/SupportService.js";

export default {
    name: "SupportTicket",
    components: {Breadcrumbs},
    data() {
        return {
            message: '',
            ticket: {
                messages: []
            }
        }
    },
    computed: {
        breadCrumbsData() {
            return [
                {
                    title: 'Main',
                    path: '/'
                },
                {
                    title: 'Support',
                    path: '/support'
                },
                {
                    title: 'Ticket #'+this.$route.params.id,
                    path: '/support/ticket'
                },
            ];
        }
    },
    methods: {
        resize() {
            let element = this.$refs["textarea"];

            element.style.height = "52px";
            element.style.height = element.scrollHeight + "px";
        },
        scrollToBottom(){
            let element = this.$refs["chatBox"];
            element.scrollTop = element.scrollHeight;
        },
        async getTicket(withLongPolling = true) {
            await (new SupportService)
                .getTicketById(this.$route.params.id)
                .then((response) => {
                    this.ticket = response.data.data;
                    if(withLongPolling) {
                        this.checkMessages();
                    }
                    let element = this.$refs["textarea"];
                    element.style.height = "52px";
                }).catch((error) => {
                    console.log(error);
                });
        },
        async sendMessage() {
            await (new SupportService)
                .sendMessage(this.ticket.id, this.message)
                .then((response) => {
                    this.message = '';
                    this.getTicket(false);
                }).catch((error) => {
                    console.log(error);
                });
        },
        checkMessages() {
            if(this.ticket.is_closed) {
                return;
            }
            setTimeout(() => {
                this.getTicket();
            },1000 * 15);
        }
    },
    mounted() {
        this.getTicket();
    },
    watch: {
        ticket: {
            handler() {
                this.$nextTick(function () {
                    this.scrollToBottom();
                });
            },
            deep: true
        }
    }
}
</script>

<style scoped>
img {
    width: 100%;
}
</style>
