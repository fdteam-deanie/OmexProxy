<script>
import ProxyService from "../services/api/ProxyService.js";
export default {
    name: "RentPeriodsMixin",
    data(){
        return {
        }
    },
    methods: {
        async getRentPeriods() {
            await (new ProxyService)
                .getRentPeriods()
                .then(res => {
                    this.$store.commit('setRentPeriods', res.data.data);
                })
                .catch(err => {
                    console.error(err)
                })
        },
    },
    computed: {
        rentPeriods() {
            return this.$store.getters.rentPeriods;
        }
    }
}
</script>
