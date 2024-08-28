<template>
    <div class="proxy-table__pagination pagination">
        <ul class="pagination__list">
            <a
                class="pagination__link" href="javascript:void(0)"
                :class="page === 1 ? 'pagination__link--disabled' : ''"
                @click="toPreviousPage"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="8" viewBox="0 0 19 8"
                     fill="none">
                    <path
                        d="M0.646446 4.35355C0.451185 4.15829 0.451185 3.84171 0.646446 3.64645L3.82843 0.464466C4.02369 0.269204 4.34027 0.269204 4.53553 0.464466C4.7308 0.659728 4.7308 0.976311 4.53553 1.17157L1.70711 4L4.53553 6.82843C4.7308 7.02369 4.7308 7.34027 4.53553 7.53553C4.34027 7.7308 4.02369 7.7308 3.82843 7.53553L0.646446 4.35355ZM19 4.5H1V3.5H19V4.5Z"
                        fill="#1B1B1B" />
                </svg>
            </a>
            <li class="pagination__list-item"
                v-for="(link, idx) in links"
                :key="idx"
            >
                <p v-if="link.active"
                   :class="link.label === page.toString() ? 'pagination__list-text' : 'pagination__list-link'"
                >
                    {{ link.label }}
                </p>
                <a v-else
                   :class="link.label === page.toString() ? 'pagination__list-text' : 'pagination__list-link'"
                   href="javascript:void(0)"
                   @click="toPage(link.url)"
                >
                    {{ link.label }}
                </a>
            </li>
            <a
                class="pagination__link" href="javascript:void(0)"
                :class="page === lastPage ? 'pagination__link--disabled' : ''"
                @click="toNextPage"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="8"
                     viewBox="0 0 19 8" fill="none">
                    <path
                        d="M18.3536 4.35355C18.5488 4.15829 18.5488 3.84171 18.3536 3.64645L15.1716 0.464466C14.9763 0.269204 14.6597 0.269204 14.4645 0.464466C14.2692 0.659728 14.2692 0.976311 14.4645 1.17157L17.2929 4L14.4645 6.82843C14.2692 7.02369 14.2692 7.34027 14.4645 7.53553C14.6597 7.7308 14.9763 7.7308 15.1716 7.53553L18.3536 4.35355ZM0 4.5H18V3.5H0V4.5Z"
                        fill="#1B1B1B" />
                </svg>
            </a>
        </ul>
    </div>
</template>

<script>
export default {
    name: "PaginationComponent",
    props: {
        pages: {
            type: Array,
            require: true
        },
        page: {
            type: Number,
            require: true
        },
        lastPage: {
            type: Number,
            require: true
        }
    },
    methods: {
        toPreviousPage() {
            if(this.page > 1) {
                this.setPage(this.page - 1);
            }
        },
        toNextPage() {
            if(this.page < this.lastPage) {
                this.setPage(this.page + 1);
            }
        },
        toPage(url) {
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const page = urlParams.get('page') || 1
            this.setPage(page);

        },
        setPage(page) {
            this.$emit('setPage', parseInt(page))
        }
    },
    computed: {
        links (){
            return this.pages.slice(1,-1);
        },
    }
}
</script>

<style scoped>

</style>
