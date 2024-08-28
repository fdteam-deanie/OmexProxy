<template>
    <div>
        <Head title="Proxy Hosting Management Tool"/>

        <Heading class="mb-6">Proxy Hosting Management Tool</Heading>
        <Card class="flex flex-col mb-3 py-3 px-3" v-for="continent in continents" :key="continent.id">
            <Disclosure as="div" class="border-gray-200" v-slot="{ open }">
                <h3 class="-my-3 flow-root">
                    <DisclosureButton
                        class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                        <span class="font-medium text-gray-900">{{ continent.name }}</span>
                        <span class="ml-6 flex items-center">
                      <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true"/>
                      <MinusIcon v-else class="h-5 w-5" aria-hidden="true"/>
                    </span>
                    </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                    <div class="space-y-3">
                        <div
                            v-if="continent.countries && continent.countries.length > 0"
                            v-for="country in continent.countries"
                            :key="country.id"
                            class="flex items-center"
                        >
                            <a :href="`/nova/resources/countries/${country.id}`">
                                <span>• {{ country.name }} - </span>
                                <span>Хосты: {{ country.country_ips_count}}, </span>
                                <span>Прокси: {{ country.ip_proxies_count }}</span>
                            </a>
                        </div>
                        <div v-else>
                            <p class="text-sm text-gray-500">
                                No countries found.
                            </p>
                        </div>
                    </div>
                </DisclosurePanel>
            </Disclosure>
        </Card>
    </div>
</template>

<script>
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from '@headlessui/vue'

import {MinusIcon, PlusIcon} from '@heroicons/vue/20/solid'

export default {
    components: {
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        MinusIcon,
        PlusIcon
    },
    data() {
        return {
            continents: []
        }
    },
    mounted() {
        this.getContinents();
    },
    methods: {
        getContinents() {
            Nova.request().get('/nova-vendor/hosting-management/countries')
                .then(res => {
                    this.continents = res.data;
                    console.log(res.data)
                })
                .catch(err => {
                    console.error(err)
                });
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
