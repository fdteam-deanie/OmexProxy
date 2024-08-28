<template>
    <div class="wrapper">
        <main class="main">
            <Breadcrumbs :bread-crumbs-data="breadCrumbsData"/>
            <section class="main__news news">
                <div class="container">
                    <div class="news__heading heading heading--centered" data-aos="fade-down">
                        <h1 class="heading__title">
                            News
                        </h1>
                    </div>
                    <div class="news__tubs news-tubs" data-aos="zoom-in">
                        <ul class="news-tubs__list">
                            <li
                                class="news-tubs__list-item"
                                v-for="category in categories"
                                :key="category.id"
                                @click="selectCategory(category)"
                            >
                                <button
                                    class="news-tubs__button"
                                    :class="{'news-tubs__button--active': selectedCategory.id === category.id}"
                                    type="button"
                                    :id="category.id"
                                >
                                    {{ category.name}}
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div v-if="articles && articles.length > 0" class="news__innner">
                        <Article
                            v-for="article in articles"
                            :key="article.id"
                            :article="article"
                        />
                    </div>
                    <div v-else class="news__no_content">
                        <h3>No news</h3>
                    </div>
                    <div v-if="pagination.last_page > 1" class="news__pagination pagination" data-aos="zoom-out">
                        <a
                            class="pagination__link"
                            :class="{'pagination__link--disabled': pagination.current_page === 1}"
                            href="#"
                            @click="prevPage"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="8" viewBox="0 0 19 8"
                                 fill="none">
                                <path
                                    d="M0.646446 4.35355C0.451185 4.15829 0.451185 3.84171 0.646446 3.64645L3.82843 0.464466C4.02369 0.269204 4.34027 0.269204 4.53553 0.464466C4.7308 0.659728 4.7308 0.976311 4.53553 1.17157L1.70711 4L4.53553 6.82843C4.7308 7.02369 4.7308 7.34027 4.53553 7.53553C4.34027 7.7308 4.02369 7.7308 3.82843 7.53553L0.646446 4.35355ZM19 4.5H1V3.5H19V4.5Z"
                                    fill="#1B1B1B" />
                            </svg>
                        </a>
                        <ul class="pagination__list">
                            <li
                                v-for="page in pagination.last_page"
                                :key="page"
                                class="pagination__list-item"
                            >
                                <p v-if="page === pagination.current_page" class="pagination__list-text">
                                    {{ page }}
                                </p>
                                <a v-else
                                   class="pagination__list-link"
                                   href="#"
                                   @click="selectPage(page)"
                                >
                                    {{ page }}
                                </a>
                            </li>
                            <a
                                class="pagination__link"
                                :class="{'pagination__link--disabled': pagination.current_page === pagination.last_page}"
                                href="#"
                                @click="nextPage"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="8" viewBox="0 0 19 8"
                                     fill="none">
                                    <path
                                        d="M18.3536 4.35355C18.5488 4.15829 18.5488 3.84171 18.3536 3.64645L15.1716 0.464466C14.9763 0.269204 14.6597 0.269204 14.4645 0.464466C14.2692 0.659728 14.2692 0.976311 14.4645 1.17157L17.2929 4L14.4645 6.82843C14.2692 7.02369 14.2692 7.34027 14.4645 7.53553C14.6597 7.7308 14.9763 7.7308 15.1716 7.53553L18.3536 4.35355ZM0 4.5H18V3.5H0V4.5Z"
                                        fill="#1B1B1B" />
                                </svg>
                            </a>
                        </ul>
                    </div>
                </div>
                <img class="news__bg" src="../../../images/news-bg.png" alt="bg">
            </section>
        </main>
    </div>
</template>

<script>

import NewsService from "../../services/api/NewsService.js";
import Article from "../../components/News/Article.vue";
import Breadcrumbs from "../../components/Common/Breadcrumbs.vue";
export default {
    name: "NewsList",
    components: {Breadcrumbs, Article},
    data() {
        return {
            categories: [],
            selectedCategory: null,
            articles: [],
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 1
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
                    title: 'News',
                    path: '/news'
                }
            ];
        }
    },
    methods: {
        async getCategories() {
            await (new NewsService)
                .getCategories()
                .then((res) => {
                    this.categories = res.data.categories;
                    if (this.categories.length > 0) {
                        this.selectCategory(this.categories[0])
                    }
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        },
        async getArticles() {
            await (new NewsService)
                .getArticles(this.selectedCategory.id, this.pagination.current_page)
                .then((res) => {
                    this.articles = res.data.data;
                    this.pagination = res.data.meta;
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        },
        async selectCategory(category)
        {
            this.selectedCategory = category;
            this.page = 1;
            await this.getArticles();
        },
        async selectPage(page)
        {
            this.pagination.current_page = page;
            await this.getArticles();
        },
        async nextPage()
        {
            if(this.pagination.current_page < this.pagination.last_page) {
                this.pagination.current_page++;
                await this.getArticles();
            }
        },
        async prevPage()
        {
            if(this.pagination.current_page > 1) {
                this.pagination.current_page--;
                await this.getArticles();
            }
        }
    },
    mounted() {
        this.getCategories();
    }
}
</script>

<style scoped>
.pagination a {
    background: none !important;
}
</style>
