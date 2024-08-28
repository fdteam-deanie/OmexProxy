<script setup>
import BTC from "../icons/BTC.vue";
import LTC from "../icons/LTC.vue";

const props = defineProps({
    coinType: String,
    balance: Number
})
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105 md:col-span-3 min-h-40 h-full" >
        <div class="p-1 bg-primary-500">

        </div>
        <div class="p-8">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-400 mb-4">{{ coinType }}</h2>
            <p class=" mb-6">Balance of {{ coinType.toLowerCase() }} wallet</p>
            <p class="text-4xl font-bold text-gray-800 dark:text-gray-400 mb-6 flex">
                <BTC
                    v-if="coinType === 'Bitcoin'"
                    class="w-10 h-10 mr-2"
                />
                <LTC
                    v-if="coinType === 'Litecoin'"
                    class="w-10 h-10 mr-2"
                />
                {{ balance }}
            </p>
        </div>
        <div class="p-4">
            <button
                class="w-full bg-primary-500 text-white rounded-full px-4 py-2 hover:bg-primary-600 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring active:bg-blue-800"
                @click="$emit('withdraw')"
            >
                Withdraw
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
