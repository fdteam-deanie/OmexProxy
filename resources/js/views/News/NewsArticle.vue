<template>
    <div v-if="article" class="wrapper">
        <main class="main">
            <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
            <section class="main__news news" data-aos="fade-up">
                <div class="container">
                    <div class="news-article__poster news-article-poster">
                        <img
                            v-if="article.image"
                            class="news-article-poster__img"
                            :src="'/storage/'+article.image"
                            alt="img"
                        >
                        <img
                            v-else
                            class="news-article-poster__img"
                            src="../../../images/news/stub.png"
                            alt="img"
                        >
                        <time class="news-article-poster__date">
                            {{ article.created_at }}
                        </time>
                    </div>
                    <h2 class="news-article__title">
                        {{ article.title }}
                    </h2>
                    <div class="markdown">
                        <vue-markdown :source="article.body"></vue-markdown>
                    </div>
                </div>
                <img class="news__bg" src="../../../images/news-bg.png" alt="bg">
            </section>
        </main>
    </div>
</template>

<script>
import NewsService from "../../services/api/NewsService.js";
import Breadcrumbs from "../../components/Common/Breadcrumbs.vue";
import VueMarkdown from 'vue-markdown-render'
export default {
    name: "Article",
    components: {Breadcrumbs, VueMarkdown},
    data() {
        return {
            article: null
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
                    title: 'News',
                    path: '/news'
                },
                {
                    title: this.article.title,
                    path: '/news/'+this.article.id
                }
            ];
        }
    },
    methods: {
        async getArticle() {
            await (new NewsService)
                .getArticle(this.$route.params.id)
                .then((res) => {
                    this.article = res.data.data;
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        }
    },
    mounted() {
        this.getArticle();
    }
}
</script>

<style scoped>

</style>
