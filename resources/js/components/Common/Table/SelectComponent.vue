<template>
    <div class="options-form-box__select custom-select">
        <div
            class="select-selected"
            :class="opened ? 'select-arrow-active' : ''"
            @click="opened = !opened"
        >
            {{ selectedOption.title }}
        </div>
        <div
            class="select-items"
            :class="!opened ? 'select-hide' : ''"
        >
            <div
                v-for="(option,  idx) in options"
                :key="idx"
                @click="emitValue(option.value)"
                :class="option.selected ? 'same-as-selected': ''"
            >
                {{ option.title }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
name: "SelectComponent",
    props: {
        options: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            opened: false
        }
    },
    methods: {
        emitValue(value) {
            this.opened = false;

            this.$emit('selectedValue', value)
        }
    },
    computed: {
        selectedOption(){
            return this.options.find(option => option.selected)
        }
    }
}
</script>

<style scoped>

</style>
