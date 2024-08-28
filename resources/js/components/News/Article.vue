<template>
    <div class="news__item news-item" data-aos="flip-up">
        <router-link :to="'/news/'+article.id" class="news-item__poster news-item-poster">
            <img
                v-if="article.image"
                class="news-item-poster__img"
                :src="'storage/'+article.image"
                alt="img"
            >
            <img
                v-else
                class="news-item-poster__img"
                src="../../../images/news/stub.png"
                alt="img"
            >
            <time class="news-item-poster__date">
                {{ article.created_at }}
            </time>
        </router-link>
        <h3 class="news-item__title">
            {{ article.title }}
        </h3>
        <p class="news-item__text">
            {{ article.short_description }}
        </p>
        <router-link :to="'/news/'+article.id" class="news-item__link">
            <span>
                Read more
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="9" viewBox="0 0 21 9"
                 fill="none">
                <path
                    d="M20.3536 4.85355C20.5488 4.65829 20.5488 4.34171 20.3536 4.14645L17.1716 0.964466C16.9763 0.769204 16.6597 0.769204 16.4645 0.964466C16.2692 1.15973 16.2692 1.47631 16.4645 1.67157L19.2929 4.5L16.4645 7.32843C16.2692 7.52369 16.2692 7.84027 16.4645 8.03553C16.6597 8.2308 16.9763 8.2308 17.1716 8.03553L20.3536 4.85355ZM0 5H20V4H0V5Z"
                    fill="#4470FE"/>
            </svg>
        </router-link>
    </div>
</template>

<script>
export default {
    name: "Article",
    props: {
        article: {
            type: Object,
            required: true
        }
    },
    computed: {
        articleImage() {
            return this.article.image ? this.article.image : '../../images/news-img-1.jpg';
        }
    }
}
</script>

<style scoped>

</style>
