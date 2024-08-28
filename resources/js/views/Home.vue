<script>
import Banner from "../components/Home/Banner.vue";
import Advantages from "../components/Home/Advantages.vue";
import AboutProxy from "../components/Home/AboutProxy.vue";
import AboutVerification from "../components/Home/AboutVerification.vue";
import Support from "../components/Home/Support.vue";
import Competitors from "../components/Home/Competitors.vue";
import More from "../components/Home/More.vue";
import Map from "../components/Home/Map.vue";
import Pricing from "../components/Home/Pricing.vue";
import Faq from "../components/Home/Faq.vue";
import Partners from "../components/Home/Partners.vue";

export default {
    name: "Home",
    components: {
        Partners,
        Faq,
        Pricing,
        Map,
        More,
        Competitors,
        Support,
        AboutVerification,
        AboutProxy,
        Advantages,
        Banner
    },
}
</script>

<template>
    <main class="main">
        <Banner/>
        <Partners/>
        <Advantages/>
        <AboutProxy/>
        <AboutVerification/>
        <Support/>
        <Competitors/>
        <More/>
        <Map/>
        <Pricing/>
        <Faq/>
    </main>
</template>

<style scoped>

</style>
