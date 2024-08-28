<template>
    <div class="purchase-info__box purchase-info-box purchase-info-box--empty">
        <div class="purchase-info-box__inner">
            <div class="purchase-info-box__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                     viewBox="0 0 20 20" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M8.33373 1.875C8.05764 1.87486 7.78718 1.95311 7.5538 2.10063C7.32043 2.24814 7.13372 2.45888 7.01539 2.70833C6.44539 2.71333 6.00206 2.73917 5.60539 2.89417C5.13187 3.07939 4.72005 3.39418 4.41706 3.8025C4.11123 4.21333 3.96706 4.74167 3.77039 5.4675L3.73123 5.60917L3.30373 8.07917C3.14956 8.18583 3.01539 8.3125 2.89873 8.46167C2.14789 9.4225 2.50539 10.8533 3.22039 13.7125C3.67456 15.5317 3.90206 16.4408 4.58039 16.9708C5.25873 17.5 6.19623 17.5 8.07123 17.5H11.9296C13.8046 17.5 14.7421 17.5 15.4204 16.9708C16.0987 16.4408 16.3254 15.5317 16.7804 13.7125C17.4954 10.8525 17.8529 9.42333 17.1021 8.46167C16.9873 8.3138 16.8504 8.1845 16.6962 8.07833L16.2687 5.61L16.2304 5.46833C16.0329 4.74167 15.8887 4.21333 15.5837 3.80167C15.2806 3.39365 14.8688 3.07916 14.3954 2.89417C13.9987 2.73917 13.5546 2.71333 12.9854 2.70917C12.8672 2.45956 12.6805 2.24865 12.4471 2.10098C12.2138 1.9533 11.9432 1.87494 11.6671 1.875H8.33373ZM15.3487 7.62167L15.0471 5.88167C14.8112 5.015 14.7254 4.7425 14.5804 4.5475C14.4172 4.32759 14.1954 4.15807 13.9404 4.05833C13.7571 3.98667 13.5354 3.96583 12.9837 3.96C12.8653 4.20888 12.6787 4.41911 12.4457 4.5663C12.2127 4.7135 11.9427 4.79164 11.6671 4.79167H8.33373C8.0581 4.79164 7.78812 4.7135 7.55509 4.5663C7.32205 4.41911 7.1355 4.20888 7.01706 3.96C6.46456 3.96583 6.24373 3.98667 6.06039 4.05833C5.80537 4.15807 5.58357 4.32759 5.42039 4.5475C5.27539 4.7425 5.18956 5.01417 4.95373 5.88083L4.65206 7.62167C5.48373 7.5 6.59206 7.5 8.07123 7.5H11.9296C13.4087 7.5 14.5171 7.5 15.3487 7.62167ZM6.66706 10.2083C6.83282 10.2083 6.99179 10.2742 7.109 10.3914C7.22621 10.5086 7.29206 10.6676 7.29206 10.8333V14.1667C7.29206 14.3324 7.22621 14.4914 7.109 14.6086C6.99179 14.7258 6.83282 14.7917 6.66706 14.7917C6.5013 14.7917 6.34233 14.7258 6.22512 14.6086C6.10791 14.4914 6.04206 14.3324 6.04206 14.1667V10.8333C6.04206 10.6676 6.10791 10.5086 6.22512 10.3914C6.34233 10.2742 6.5013 10.2083 6.66706 10.2083ZM13.9587 10.8333C13.9587 10.6676 13.8929 10.5086 13.7757 10.3914C13.6585 10.2742 13.4995 10.2083 13.3337 10.2083C13.168 10.2083 13.009 10.2742 12.8918 10.3914C12.7746 10.5086 12.7087 10.6676 12.7087 10.8333V14.1667C12.7087 14.3324 12.7746 14.4914 12.8918 14.6086C13.009 14.7258 13.168 14.7917 13.3337 14.7917C13.4995 14.7917 13.6585 14.7258 13.7757 14.6086C13.8929 14.4914 13.9587 14.3324 13.9587 14.1667V10.8333ZM10.0004 10.2083C10.1662 10.2083 10.3251 10.2742 10.4423 10.3914C10.5595 10.5086 10.6254 10.6676 10.6254 10.8333V14.1667C10.6254 14.3324 10.5595 14.4914 10.4423 14.6086C10.3251 14.7258 10.1662 14.7917 10.0004 14.7917C9.83463 14.7917 9.67566 14.7258 9.55845 14.6086C9.44124 14.4914 9.37539 14.3324 9.37539 14.1667V10.8333C9.37539 10.6676 9.44124 10.5086 9.55845 10.3914C9.67566 10.2742 9.83463 10.2083 10.0004 10.2083Z"
                          fill="#4470FE" />
                </svg>
            </div>
            <h4 class="purchase-info-box__title">
                <span v-show="cartTotal <= 0">Cart empty</span>
                <span v-show="cartTotal > 0">Cart<span v-show="!hasActiveUnlimitedSubscription"> - ${{ cartTotal }}</span></span>
            </h4>
        </div>
        <p
            class="purchase-info-box__text"
            v-show="cartTotal <= 0"
        >
            Click on proxy to buy it
        </p>
        <div v-show="cartTotal > 0" class="items smaller">
            <ul>
                <li v-for="cartItem in cartItems">
                    <div class="item_flag" :style="{ backgroundImage: `url(${cartItem.country_data.flag})` }"></div>
                    <div class="item_ip">{{ cartItem.ip }}</div>
                    <div class="item_price" v-show="!hasActiveUnlimitedSubscription">${{ cartItem.price }}</div>
                    <button type="button" @click="emitDeletingFromCart(cartItem.id);" class="remove"></button>
                </li>
            </ul>
            <br />

            <div v-if="hasActiveUnlimitedSubscription">
                <h2 class="margin_top_10">You have active unlimited subscription</h2>
                <p>Expired at: {{unlimitedSubscription?.expired_at}}</p>
            </div>
        </div>
        <div class="purchase__buttons">
            <button
                class="purchase__buttons-btn btn btn--blue"
                type="button"
                @click="emitShowCartModal"
                :disabled="cartTotal <= 0"
            >
                <span v-if="hasActiveUnlimitedSubscription">Buy</span>
                <span v-else>Buy now ${{ cartTotal }}</span>
            </button>
            <button
                class="purchase__buttons-btn btn btn--secondary"
                type="button"
                v-if="!hasActiveUnlimitedSubscription"
                @click="emitShowBuyUnlimitedModal"
            >
                <span>Buy unlimited ${{unlimitedSubscriptionPrice}}</span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "CartSection",
    props: [
        'hasActiveUnlimitedSubscription',
        'unlimitedSubscription',
        'unlimitedSubscriptionPrice'
    ],
    methods: {
        emitDeletingFromCart(cartItemId) {
            this.$emit('deleteFromCart', cartItemId);
        },
        emitShowCartModal() {
            this.$emit('showCartModal');
        },
        emitShowBuyUnlimitedModal() {
            this.$emit('showBuyUnlimitedModal');
        }
    },
    computed: {
        cartTotal() {
            return this.$store.getters.cartTotal
        },
        cartItems() {
            return this.$store.getters.cartItems
        }
    }
}
</script>

<style scoped>
    .margin_top_10 {
        margin-top: 10px !important;
    }
</style>
