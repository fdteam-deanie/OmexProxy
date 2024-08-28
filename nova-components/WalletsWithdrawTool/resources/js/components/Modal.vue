<script>
export default {
    name: 'Modal',
    props: {
        isOpen: {
            type: Boolean,
            required: true
        },
        modalStatus: {
            type: String,
            required: true
        },
        modalMessage: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            isModalOpen: false,
            messageType: '', // 'success' або 'error'
            message: '',
        };
    },
    methods: {
        // openModal() {
        //     // Приклад успішного повідомлення
        //     this.messageType = 'success';
        //     this.message = 'Операція виконана успішно!';
        //     this.isModalOpen = true;
        //
        //     // Або приклад повідомлення про помилку
        //     // this.messageType = 'error';
        //     // this.message = 'Помилка при виконанні операції.';
        //     // this.isModalOpen = true;
        // },
        closeModal() {
            this.$emit('close-modal');
            // this.isModalOpen = false;
        },
    },
};
</script>

<template>
    <div>
        <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center bg-black modal_background">
            <div class="bg-gray-300 p-8 rounded shadow-md">
                <!-- Заголовок модалки -->
                <h2 class="text-2xl mb-4" :class="{ 'text-green-500': modalStatus === 'success', 'text-red-600': modalStatus === 'error' }">
                    {{ modalStatus === 'success' ? 'Успіх!' : 'Помилка!' }}
                </h2>

                <!-- Текст повідомлення -->
                <p class="mb-4 text-gray-800">{{ modalMessage }}</p>

                <!-- Кнопка для закриття модалки -->
                <button @click="closeModal" class="bg-primary-500 hover:bg-primary-600 px-4 py-2 rounded text-white">
                    Закрити
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .bg-black{
        --tw-bg-opacity: 0.75;
        background-color: rgb( 0 0 0/var( --tw-bg-opacity ) )
    }
</style>
