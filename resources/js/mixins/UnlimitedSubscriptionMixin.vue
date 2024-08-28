<script>
import UnlimitedSubscriptionService from "../services/api/UnlimitedSubscriptionService.js";
export default {
    name: "RentPeriodsMixin",
    data(){
        return {
        }
    },
    methods: {
        async getActiveUnlimitedSubscription() {
            await (new UnlimitedSubscriptionService)
                .getActiveUnlimitedSubscription()
                .then(res => {
                    this.$store.commit('setUnlimitedSubscriptionData', {
                        unlimitedSubscription: res.data.subscription,
                        unlimitedSubscriptionPrice: res.data.price,
                    });
                })
                .catch(err => {
                    console.error(err)
                })
        },
    },
    computed: {
        hasActiveUnlimitedSubscription() {
            return this.$store.getters.hasActiveUnlimitedSubscription;
        },
        unlimitedSubscription() {
            return this.$store.getters.unlimitedSubscription;
        },
        unlimitedSubscriptionPrice() {
            return this.$store.getters.unlimitedSubscriptionPrice;
        },
    }
}
</script>
