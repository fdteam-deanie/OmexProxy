<template>
    <Card>
        <div class="p-6">
            <Heading class="mb-3">Проверить прокси</Heading>
            <div class="mb-6">
                <LoadingButton
                    @click="check"
                    :loading="checkLoading"
                >
                    Проверить
                </LoadingButton>
                <span class="ml-3 text-green" v-if="isOk && isChecked">
                    <Icon type="check-circle"></Icon>
                    <span class="ml-1">Соединение успешно</span>
                </span>
                <span class="ml-3 text-red-400" v-if="!isOk && isChecked">
                    <Icon type="x-circle"></Icon>
                    <span class="ml-1">Нет соединения</span>
                </span>
            </div>

            <Heading class="mb-3">Заменить прокси</Heading>
            <div class="mb-6">
                <SearchInput
                    @clear="clearProxy"
                    @selected="selectProxy"
                    :value="selectedProxy"
                    :data="proxies"
                    trackBy="id"
                    class="w-full mb-3"
                >
                    <!-- The Selected Option Slot -->
                    <div v-if="selectedProxy" class="flex items-center">
                        {{ selectedProxy.ip }}
                    </div>

                    <template #option="{ selected, option }">
                        <!-- Options List Slot -->
                        <div
                            class="flex items-center text-sm font-semibold leading-5"
                            :class="{ 'text-white': selected }"
                        >
                            {{ option.ip }}
                        </div>
                    </template>
                </SearchInput>
                <LoadingButton
                    @click="replaceProxy"
                    :loading="saveLoading"
                    :disabled="!selectedProxy"
                >
                    Сохранить
                </LoadingButton>
                <span class="ml-3 text-green" v-if="saved">
                    <Icon type="check-circle"></Icon>
                    <span class="ml-1">Успешно сохранено</span>
                </span>
            </div>
        </div>
    </Card>
</template>

<script>
export default {
    props: ['resourceName', 'resourceId', 'panel'],
    data() {
        return {
            checkLoading: false,
            saveLoading: false,
            saved: false,
            isChecked: false,
            isOk: null,
            errorMessage: null,
            reportData: {},
            proxies: [],
            selectedProxy: null,
        }
    },
    methods: {
        check() {
            this.checkLoading = true
            Nova.request().post('/nova-vendor/proxy-checker/check', {
                id: this.resourceId
            })
                .then(response => {
                    this.checkLoading = false;
                    this.isOk = response.data.isOk;
                    this.isChecked = true;
                })
                .catch(error => {
                    this.checkLoading = false;
                    this.isOk = false;
                    this.isChecked = true;
                    this.errorMessage = error.response.data.message;
                    console.log(error.response.data);
                })
        },
        selectProxy(option) {
            this.saved = false;
            this.selectedProxy = option;
            // this.value = option.value
        },
        clearProxy()
        {
            this.selectedProxy = null;
        },
        getProxies()
        {
            Nova.request().get('/nova-vendor/proxy-checker/complaints/'+this.resourceId+'/proxies')
                .then(response => {
                    this.proxies = response.data.proxies;
                })
                .catch(error => {
                    console.log(error.response.data);
                })
        },
        replaceProxy()
        {
            this.saveLoading = true
            Nova.request().post('/nova-vendor/proxy-checker/complaints/'+this.resourceId+'/proxies/replace', {
                proxy_id: this.selectedProxy.id
            })
                .then(response => {
                    this.saveLoading = false;
                    this.selectedProxy = null;
                    this.saved = true;
                    this.getProxies();
                })
                .catch(error => {
                    this.saveLoading = false;
                    console.log(error.response.data);
                })
        }
    },
    mounted() {
        this.getProxies()
    },
}
</script>
