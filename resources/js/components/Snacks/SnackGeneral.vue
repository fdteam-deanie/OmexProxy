<template>
    <div v-if="show" class="snackbar">
        {{ message }}
        <button @click="hideSnackbar">&nbsp;&nbsp;&Cross;</button>
    </div>
</template>

<script>
export default {
    name: "snackGeneral",
    props:[ 'snackData' ],
    data() {
        return {
            show: false,
            message: ''
        };
    },
    methods: {
        showSnackbar(message) {
            this.message = message;
            this.show = true;
            setTimeout(this.hideSnackbar, 4000);
        },
        hideSnackbar() {
            this.show = false;
            this.message = '';
        }
    }
};
</script>

<style scoped>
.snackbar {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 10;
}

button {
    background: none;
    border: none;
    color: #4470FE;
    cursor: pointer;
}
</style>
