<template>
    <div class="main__breadcrumbs breadcrumbs" data-aos="fade-right">
        <div class="container">
            <div class="breadcrumbs__inner">
                <ul class="breadcrumbs__list">
                    <li
                        v-for="(item, key) in breadCrumbsData"
                        class="breadcrumbs__list-item"
                        :key="key"
                    >
                        <template v-if="key + 1 < breadCrumbsData.length">
                            <router-link
                                class="breadcrumbs__list-link"
                                :to="item.path"
                            >
                                {{ item.title }}
                            </router-link>
                            <img :src="arrow"  alt="arrow"/>
                        </template>
                        <template v-else>
                            <p class="breadcrumbs__list-text">
                                {{ item.title }}
                            </p>
                        </template>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import arrow from '../../../images/icons/arrow_right_grey.svg'
export default {
    name: "Breadcrumbs",
    props: {
        breadCrumbsData: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            arrow
        }
    }
}
</script>

<style scoped>

</style>
