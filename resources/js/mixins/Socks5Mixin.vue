<script>
import UserService from "../services/api/UserService.js";
export default {
    name: "Socks5Mixin",
    data(){
        return {
            socks5: {
                username: '',
                password: ''
            },
        }
    },
    methods: {
        async getSocks5Creds() {
            await (new UserService)
                .getSocks5Credentials()
                .then(res => {
                    const data = res.data;
                    if(null !== data.result.username && null !== data.result.password) {
                        this.$store.commit('setSocks5Auth', data.result);
                    }
                })
                .catch(err => {
                    console.error(err)
                })
        },
    },
    computed: {
        socks5Auth() {
            return this.$store.getters.socks5Auth;
        }
    }
}
</script>
