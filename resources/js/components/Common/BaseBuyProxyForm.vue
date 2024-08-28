<template>
    <div>
        <select v-if="!hasActiveUnlimitedSubscription" v-model="days" class="form_input rent_days_input w-full">
            <option :key="period.id" :value="period.days" v-for="period in rentPeriods">
                {{ period.name }}
            </option>
        </select>
        <button
            type="button"
            @click="submit"
            class="account-form__button btn btn--blue w-full"
            :disabled="loading"
        >
            <span>
                {{text}}
                <span v-if="!hasActiveUnlimitedSubscription">
                    ${{ Number(proxy.price * days).toFixed(2) }}
                </span>
            </span>
        </button>
        <div v-if="showMessage" class="alert badge" :class="alertClass">{{ message }}</div>
    </div>
</template>

<script>
import RentPeriodsMixin from "../../mixins/RentPeriodsMixin.vue";
import UnlimitedSubscriptionMixin from "../../mixins/UnlimitedSubscriptionMixin.vue";
export default {
    name: "BaseBuyProxyForm",
    mixins: [RentPeriodsMixin, UnlimitedSubscriptionMixin],
    props: {
        proxy: {
            type: Object,
            required: true
        },
        text: {
            type: String,
            required: true
        },
        loading: {
            type: Boolean,
            required: true
        },
        showMessage: {
            type: Boolean,
            default: true
        },
        message: {
            type: String,
            default: ""
        },
        messageType: {
            type: String,
            default: "error"
        }
    },
    data() {
        return {
            days: 1
        }
    },
    computed: {
        alertClass() {
            if(this.messageType === 'error') {
                return 'alert_red';
            } else {
                return '';
            }
        }
    },
    methods: {
        submit() {
            this.$emit('submit', this.proxy, this.days);
        }
    },
    mounted() {
        this.getRentPeriods();
    }
}
</script>

<style scoped>
.rent_days_input {
    border-radius: 4px;
    border: 1px solid #d9d9d9;
    text-transform: uppercase;
    height: 50px;
    padding: 0 0 0 10px;
    font-size: 12px;
    color: #797979;
    margin-bottom: 15px;
}
.submit_btn {
    margin-bottom: 10px;
}
</style>
