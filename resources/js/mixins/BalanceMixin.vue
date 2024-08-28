<script>
import UserService from "../services/api/UserService.js";

export default {
    name: "BalanceMixin",
    methods: {
        async getBalance() {
            await (new UserService)
                .getBalance()
                .then(res => {
                    const data = res.data;
                    if('success' === data.status) {
                        this.$store.commit('setUserBalance', data.balance);
                        this.$store.commit('setUserBonusCredits', data.bonus_credits);
                        this.$store.commit('setUserCredits', data.user_credits);

                    }
                })
                .catch(err => {
                    console.error(err)
                })
        }
    },
    computed: {
        userBalance() {
            return Number(this.$store.getters.userBalance).toFixed(2);
        },
        userBonusCredits() {
            return Number(this.$store.getters.userBonusCredits).toFixed(2);
        },
        userCredits() {
            return Number(this.$store.getters.userCredits).toFixed(2);;
        }
    }
}
</script>
