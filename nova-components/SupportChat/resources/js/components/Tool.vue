<template>
    <div class="msger-chat" ref="chatBox">
        <template
            v-for="(messages, key) in ticket.messages"
        >
            <p class="msger-date">{{ key }}</p>
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
                        <div class="msg-info-name">{{ message.from }}</div>
                        <div class="msg-info-time">{{ message.time }}</div>
                    </div>

                    <div class="msg-text">
                        <img v-if="message.is_image" :src="'/storage/'+message.body" alt="">
                        <span v-else>{{ message.body }}</span>
                    </div>
                </div>
            </div>
        </template>
        <p v-if="ticket.is_closed" class="msger-date">Тикет закрыт</p>
    </div>

    <form class="msger-inputarea">
        <button
            v-if="!ticket.is_closed"
            type="button"
            class="msger-close-btn"
            @click="closeTicket()"
        >
            Закрыть
        </button>
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
            :disabled="ticket.is_closed || message == ''"
            class="msger-send-btn"
            @click="sendMessage()"
        >
            Отправить
        </button>
    </form>
</template>

<script>

export default {
    props: ['resourceName', 'resourceId', 'panel'],
    data() {
        return {
            ticket: {},
            message: ''
        }
    },
    methods: {
        resize() {
            let element = this.$refs["textarea"];

            element.style.height = "52px";
            element.style.height = element.scrollHeight + "px";
        },
        scrollToBottom() {
            let element = this.$refs["chatBox"];
            element.scrollTop = element.scrollHeight;
        },
        async getTicket() {
            await Nova.request()
                .get('/nova-vendor/support-chat/tickets/' + this.resourceId)
                .then((response) => {
                    this.ticket = response.data.data;
                    this.checkMessages();
                }).catch((error) => {
                    console.log(error);
                });
        },
        async getMessages(withLongPoll = true) {
            await Nova.request()
                .get('/nova-vendor/support-chat/tickets/' + this.resourceId + '/messages')
                .then((response) => {
                    this.ticket.messages = response.data.data;
                    if(withLongPoll) {
                        this.checkMessages();
                    }
                }).catch((error) => {
                    console.log(error);
                });
        },
        async sendMessage() {
            await Nova.request()
                .post('/nova-vendor/support-chat/tickets/' + this.resourceId + '/messages', {
                    message: this.message
                })
                .then((response) => {
                    this.message = '';
                    let element = this.$refs["textarea"];
                    element.style.height = "52px";
                    this.getMessages(false);
                }).catch((error) => {
                    console.log(error);
                });
        },
        async closeTicket() {
            await Nova.request()
                .post('/nova-vendor/support-chat/tickets/' + this.resourceId + '/close')
                .then((response) => {
                    this.getTicket();
                }).catch((error) => {
                    console.log(error);
                });
        },
        checkMessages() {
            setTimeout(() => {
                this.getMessages();
            },1000 * 15);
        }
    },
    mounted() {
        this.getTicket();
        Nova.$on('nova-tabs-changed', (panel, tab) => {
            this.$nextTick(() => this.scrollToBottom())
        })
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
