<template>
    <LoadingButton
        class="mb-3 mt-3"
        @click="generateProxy"
        :loading="loading"
    >
        Сгенерировать прокси
    </LoadingButton>
    <span class="block mt-3 mb-3" :class="isError ? 'text-red-500' : 'text-green'" v-if="showMessage">
        <span>{{message}}</span>
    </span>
</template>

<script>
export default {
    props: ['resourceName', 'resourceId', 'panel'],
    data() {
        return {
            loading: false,
            message: null,
            showMessage: false,
            isError: false
        }
    },
    methods: {
        generateProxy() {
            this.loading = true
            Nova.request().post('/nova-vendor/generate-proxy', {
                id: this.resourceId
            })
                .then(response => {
                    this.loading = false;
                    this.showMessage = true;
                    this.message = response.data.message;

                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                })
                .catch(error => {
                    this.loading = false;
                    this.showMessage = true;
                    this.isError = true;
                    this.message = error.response.data.message;
                    console.log(error.response.data);
                })
        }
    },
    mounted() {
        //
    },
}
</script>
