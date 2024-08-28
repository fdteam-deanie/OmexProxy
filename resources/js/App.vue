<template>
    <AppNewHeader/>
    <div class="wrapper">
        <router-view></router-view>
        <AppNewFooter/>
    </div>
</template>
<script>
	import AuthMixin from "./mixins/AuthMixin.vue";
    import AppNewHeader from "./components/Common/AppNewHeader.vue";
    import AppNewFooter from "./components/Common/AppNewFooter.vue";

    export default {
        components: {
            AppNewHeader,
            AppNewFooter,
        },
        mixins:[ AuthMixin ],
		data() {
			return {
				testString: 'Правки роутера'
			}
		},
        beforeMount() {
            this.checkAuth();
        },
	}
</script>
