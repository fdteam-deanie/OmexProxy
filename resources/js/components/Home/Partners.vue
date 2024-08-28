<template>
    <section class="main__partners partners" data-aos="zoom-in">
        <div class="partners__inner">


            <swiper
                :slides-per-view="8"
                :space-between="20"
                :loop="!0"
                :initialSlide="0"
                :centeredSlides="!0"
                :speed="750"
                :spaceBetween="20"
                :autoplay="{ delay: 2500 }"
                class="partners__swiper partners-swiper"
                @swiper="onSwiper"
                @slideChange="onSlideChange"
            >
                <swiper-slide>
                    <div class="partners-slide__img">
                        <img class="partners-slide__img-image" src="../../../images/partners-img-1.png" alt="img">
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="partners-slide__img">
                        <img class="partners-slide__img-image" src="../../../images/partners-img-2.png" alt="img">
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="partners-slide__img">
                        <img class="partners-slide__img-image" src="../../../images/partners-img-3.png" alt="img">
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="partners-slide__img">
                        <img class="partners-slide__img-image" src="../../../images/partners-img-4.png" alt="img">
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="partners-slide__img">
                        <img class="partners-slide__img-image" src="../../../images/partners-img-1.png" alt="img">
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="partners-slide__img">
                        <img class="partners-slide__img-image" src="../../../images/partners-img-2.png" alt="img">
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="partners-slide__img">
                        <img class="partners-slide__img-image" src="../../../images/partners-img-3.png" alt="img">
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="partners-slide__img">
                        <img class="partners-slide__img-image" src="../../../images/partners-img-4.png" alt="img">
                    </div>
                </swiper-slide>

            </swiper>
        </div>
    </section>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';

export default {
    name: "Partners",
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        const onSwiper = (swiper) => {
            // console.log(swiper);
        };
        const onSlideChange = () => {
            // console.log('slide change');
        };
        return {
            onSwiper,
            onSlideChange,
        };
    },

}
</script>

<style scoped>

</style>
