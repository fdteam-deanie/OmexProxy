<template>
    <section class="main__faq faq">
        <div class="container">
            <div class="faq__inner">
                <div class="faq__heading heading heading--centered">
                    <h2 class="heading__title" data-aos="fade-down">
                        Solve Your Usage Problems
                    </h2>
                </div>
                <ul class="faq__list faq-list" data-aos="zoom-in">
                    <li
                        v-for="(item, index) in itemsData"
                        class="faq-list__item"
                        :class="{ 'faq-list__item--active': item.isActive }"
                        :key="index"
                        @click="toggleActiveState(index)"
                    >
                        <div class="faq-list__top faq-list-top">
                            <h4 class="faq-list-top__title">
                                {{ item.title }}
                            </h4>
                            <div class="faq-list-top__arrow">
                                <svg width="17" height="11" viewBox="0 0 17 11" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.75 6.325L15.075 -8.41444e-08L17 1.925L8.75 10.175L0.5 1.925L2.425 -6.37094e-07L8.75 6.325Z"
                                        fill="#4470FE" />
                                </svg>
                            </div>
                        </div>
                        <p class="faq-list__text">
                            {{ item.text }}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
        <img class="faq__bg" src="../../../images/faq-bg.jpg" alt="bg">
    </section>
</template>

<script>
export default {
    name: "Faq",
    data() {
        return {
            itemsData: [
                {
                    title: 'Can I choose a proxy from a specific location?',
                    text: 'Yes, our easy to use location selector allows you to quickly select country, state and city locations.',
                    isActive: false
                },
                {
                    title: 'How long can my IP be kept?',
                    text: 'Yes, our easy to use location selector allows you to quickly select country, state and city locations.',
                    isActive: false
                },
                {
                    title: 'Are these rotating IPs or static IPs?',
                    text: 'Yes, our easy to use location selector allows you to quickly select country, state and city locations.',
                    isActive: false
                },
                {
                    title: 'How many IPs can I use?',
                    text: 'Yes, our easy to use location selector allows you to quickly select country, state and city locations.',
                    isActive: false
                },
                {
                    title: 'How do residential proxies work?',
                    text: 'Yes, our easy to use location selector allows you to quickly select country, state and city locations.',
                    isActive: false
                },
            ],
            isActive: false,
        }
    },
    methods: {
        toggleActiveState(idx) {
            this.itemsData.map((item, index) => {
                if (index === idx) {
                    item.isActive = !item.isActive
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
