<template>
    <NewTicketModal ref="newTicketModal" @created="getTickets"/>
    <div class="wrapper">
        <main class="main">
            <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
            <section class="main__support-tickets support-tickets">
                <div class="container">
                    <div class="support-tickets__inner">
                        <div class="support-tickets__heading heading">
                            <h3 class="heading__title" data-aos="fade-down">
                                Your tickets
                            </h3>
                            <button
                                type="button"
                                style="height: 52px"
                                class="btn flex ai_center center submit_btn"
                                @click="showNewTicketModal()"
                            >
                                New ticket
                            </button>
                        </div>
                        <ul v-if="tickets && tickets.length > 0" class="support-tickets__list support-tickets-list" data-aos="fade-up">
                            <router-link :to="'/support/' + ticket.id"
                                v-for="(ticket, index) in tickets"
                                class="support-tickets-list__item"
                                :key="index"
                            >
                                <div class="support-tickets-list__top support-tickets-list-top">
                                    <div class="support-tickets-list-top">
                                        <h4 class="support-tickets-list-top__title support-tickets-list__id">
                                            #{{ ticket.id}}
                                        </h4>
                                        <h4 class="support-tickets-list-top__title support-tickets-list__subject">
                                            <span
                                                v-if="ticket.unread_messages_count && ticket.unread_messages_count > 0"
                                                class="circle-badge"
                                            >
                                                {{ ticket.unread_messages_count}}
                                            </span>
                                            {{ ticket.subject}}
                                        </h4>
                                    </div>
                                    <h4 class="support-tickets-list-top__title support-tickets-list__date">
                                        {{ ticket.date}}
                                    </h4>
                                    <h4 class="support-tickets-list-top__title support-tickets-list__status">
                                        {{ ticket.status}}
                                    </h4>
                                    <div class="support-tickets-list-top__arrow">
                                        <svg width="17" height="11" viewBox="0 0 330 330" xml:space="preserve" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path id="XMLID_222_" fill="#4470FE" d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001
                                                c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213
                                                C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606
                                                C255,161.018,253.42,157.202,250.606,154.389z"/>
                                        </svg>
                                    </div>
                                </div>
                            </router-link>
                        </ul>
                        <div v-else class="news__no_content">
                            <h3>You don't have tickets</h3>
                        </div>
                    </div>
                </div>
                <img v-if="tickets && tickets.length > 5" class="support-tickets__bg" src="../../../images/faq-bg.jpg" alt="bg">
            </section>
        </main>
    </div>
</template>

<script>
import Breadcrumbs from "../../components/Common/Breadcrumbs.vue";
import SupportService from "../../services/api/SupportService.js";
import NewTicketModal from "../../components/Modals/NewTicketModal.vue";

export default {
    name: "Support",
    components: {Breadcrumbs, NewTicketModal},
    data() {
        return {
            tickets: []
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
                }
            ];
        }
    },
    methods: {
        async getTickets() {
            await (new SupportService)
                .getTickets()
                .then((response) => {
                    this.tickets = response.data.data;
                }).catch((error) => {
                    console.log(error);
                });
        },
        showNewTicketModal() {
            this.$refs.newTicketModal.show()
        },
    },
    mounted() {
        this.getTickets();
    }
}
</script>

<style scoped>
.container {
    padding-bottom: 20px;
}
</style>
