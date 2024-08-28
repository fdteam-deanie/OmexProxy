<template>
    <div class="account-form__wrapper">
        <div class="account-form__box" :class="[activeClass, errorClass]">
            <label class="account-form__box-label" :for="field">
                {{label}}
            </label>
            <input
                class="account-form__box-input"
                :type="type"
                :id="field"
                v-model="value"
                @focus="isActive = true"
                @blur="isActive = false"
            >
        </div>
        <div class="error-message" v-if="hasError">
            {{errorMessage}}
        </div>
    </div>
</template>

<script>
export default {
    name: "Input",
    props: {
        label: {
            type: String,
            required: true
        },
        field: {
            type: String,
            default: 'text'
        },
        type: {
            type: String,
            default: 'text'
        },
        hasError: {
            type: Boolean,
            default: false
        },
        errorMessage: {
            type: String,
            default: ''
        },
        modelValue: {
            type: String,
            default: ''
        }
    },
    emits: ['update:modelValue'],
    data() {
        return {
            isActive: false
        }
    },
    computed: {
        value: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        },
        activeClass() {
            return this.isActive || this.value !== '' ? 'account-form__box--active' : ''
        },
        errorClass() {
            return this.hasError ? 'account-form__box--error' : ''
        }
    },
}
</script>

<style scoped>
.account-form__box--error
{
    border-color: #ff0000;
}

.error-message {
    width: 100%s;
    color: #cc0033;
    display: inline-block;
    font-size: 12px;
    line-height: 15px;
    margin: 5px 0 0;
}

.account-form__wrapper
{
    width: 100%;
}
</style>
