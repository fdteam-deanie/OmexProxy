<template>
    <div v-show="isShow" :id="id" class="popup" @click="hide()">
        <div class="popup_window" @click.stop>
            <div class="popup_header">
                <slot name="header"></slot>
            </div>
            <div class="popup_body">
                <slot></slot>
            </div>
            <div class="popup_footer">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true
        }
    },
    name: "Modal.vue",
    data() {
        return {
            isShow: false
        };
    },
    methods: {
        show() {
            this.isShow = true;
        },
        hide() {
            this.isShow = false;
        }
    }
}
</script>

<style scoped>

</style>
