<template>
    <main class="main">
        <section class="main__launching launching">
            <div class="container">
                <div class="launching__inner">
                    <h1 class="launching__title" data-aos="fade-down">
                        OmexAI:
                        <span>
                                Launching
                            </span>
                        Soon!
                    </h1>
                    <p class="launching__text" data-aos="fade-down">
                        Countdown to Launch:
                    </p>
                    <div class="launching__timer timer" data-aos="zoom-in" v-if="showTimer" >
                        <div class="timer__item timer-item">
                            <p class="timer-item__text timer-item__text--days">
                                {{ days }}
                                <span>:</span>
                            </p>
                            <p class="timer-item__subtext">
                                days
                            </p>
                        </div>
                        <div class="timer__item timer-item">
                            <p class="timer-item__text timer-item__text--hours">
                                {{ hours }}
                                <span>:</span>
                            </p>
                            <p class="timer-item__subtext">
                                hours
                            </p>
                        </div>
                        <div class="timer__item timer-item">
                            <p class="timer-item__text timer-item__text--minutes">
                                {{ minutes }}
                                <span>:</span>
                            </p>
                            <p class="timer-item__subtext">
                                minutes
                            </p>
                        </div>
                        <div class="timer__item timer-item">
                            <p class="timer-item__text timer-item__text--seconds">
                                {{ seconds }}
                            </p>
                            <p class="timer-item__subtext">
                                seconds
                            </p>
                        </div>
                    </div>
                    <div class="launching__timer timer" data-aos="zoom-in" v-else >
                        <span>Time is over</span>
                    </div>
                    <p class="launching__subtext" data-aos="fade-up">
                        Get ready for the next generation of proxy services. OmexAI is launching soon, bringing you
                        advanced and secure solutions for residential, mobile, and server proxies. Stay tuned for
                        our grand launch and be the first to experience the future of proxy technology.
                    </p>
                </div>
                <div class="launching__bg launching-bg">
                    <div class="launching-bg__box">
                        <img class="launching-bg__box-img" src="../../images/account-bg.png" alt="bg">
                        <img class="launching-bg__box-img" src="../../images/account-bg-figures.png" alt="bg">
                    </div>
                    <div class="launching-bg__box">
                        <img class="launching-bg__box-img" src="../../images/entrance-bg-mobile.png" alt="bg">
                        <img class="launching-bg__box-img" src="../../images/entrance-bg-figures-mobile.png" alt="bg">
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
export default {
    name: 'Launching',
    data() {
        return {
            targetDate: new Date('2024-08-16T23:59:59'),
            showTimer: true,
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00',
        }
    },
    methods: {
        updateTimer() {
            const now = new Date();
            const timeRemaining = this.targetDate - now;
            if (timeRemaining >= 0) {
                const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

                this.days = this.addLeadingZero(days);
                this.hours = this.addLeadingZero(hours);
                this.minutes = this.addLeadingZero(minutes);
                this.seconds = this.addLeadingZero(seconds);
                this.showTimer = true;
            } else {
                clearInterval(this.timerInterval);
                this.days = '00';
                this.hours = '00';
                this.minutes = '00';
                this.seconds = '00';
                this.showTimer = false;
            }
        },
        addLeadingZero(num) {
            return num < 10 ? '0' + num : num;
        }

    },
    mounted() {
        this.updateTimer();
        this.timerInterval = setInterval(this.updateTimer, 1000);
    },
    beforeDestroy() {
        clearInterval(this.timerInterval);
    },

}
</script>

<style scoped>

</style>
