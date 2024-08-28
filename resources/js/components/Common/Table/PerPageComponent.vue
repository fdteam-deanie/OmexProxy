<template>
    <div class="proxy-table__bottom proxy-table-bottom">
        <p class="proxy-table-bottom__found">
            <span>{{ total }}</span> proxies found
        </p>
        <div class="proxy-table-bottom__pagination proxy-table-bottom-pagination">
            <p class="proxy-table-bottom-pagination__text">
                <span>proxies per page</span>
            </p>
            <ul class="proxy-table-bottom-pagination__list">
                <li
                    class="proxy-table-bottom-pagination__list-item"
                    v-for="value in values"
                >
                    <button
                        class="proxy-table-bottom-pagination__list-link"
                        @click="setPerPageValue(value)"
                        :class="value === selectedValue ? 'active' : ''"
                    >
                        {{ value }}
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "PerPageComponent",
    props: {
        values: {
            type: Array,
            require: true
        },
        selectedValue: {
            type: Number,
            require: true
        },
        total: {
            type: Number,
            require: true
        }
    },
    methods: {
        setPerPageValue(value) {
            this.$emit('setValue', value);
        }
    }
}
</script>

<style scoped>

</style>
