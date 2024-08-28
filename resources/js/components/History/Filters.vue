<template>
    <div class="proxy__options options">
        <form class="options__form options-form">
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">IP</h3>
                <input class="options-form-box__input" type="text" placeholder="IP" v-model="filters.ip">
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Online</h3>
                <div class="options-form-box__select custom-select">
                    <SelectComponent
                        :options="onlineOptions"
                        @selected-value="setOnlineToFilter"
                    />
                </div>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Paid</h3>
                <div class="options-form-box__select custom-select">
                    <SelectComponent
                        :options="paidOptions"
                        @selected-value="setPaidToFilter"
                    />
                </div>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Location</h3>
                <input class="options-form-box__input" type="text" placeholder="COUNTRY">
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">&nbsp;</h3>
                <input class="options-form-box__input" type="text" placeholder="STATE">
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">&nbsp;</h3>
                <input class="options-form-box__input" type="text" placeholder="CITY">
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">&nbsp;</h3>
                <input class="options-form-box__input" type="text" placeholder="ZIP">
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">ISP</h3>
                <input class="options-form-box__input" type="text" placeholder="ISP">
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Type</h3>
                <div class="options-form-box__select custom-select">
                    <SelectComponent
                        :options="proxyTypes"
                        @selected-value="setTypeToFilter"
                    />
                </div>
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Bought</h3>
                <input class="options-form-box__input" type="text" disabled placeholder="Bought">

            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Expire</h3>
                <input class="options-form-box__input" type="text" disabled placeholder="Expire">
            </div>
            <div class="options-form__box options-form-box">
                <h3 class="options-form-box__title">Price</h3>
                <input
                    class="options-form-box__input"
                    type="text"
                    placeholder="Price"
                    pattern="[0-9.]+"
                    @keypress="priceMask($event)"
                >
            </div>
        </form>
    </div>
</template>

<script>
import SelectComponent from "../Common/Table/SelectComponent.vue";

import ProxyService from "../../services/api/ProxyService.js";

export default {
    name: "Filters",
    components: { SelectComponent },
    props: {
        filters: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            paidOptions: [
                {title: 'All', value: null, selected: true},
                {title: 'Yes', value: true, selected: false},
                {title: 'No', value: false, selected: false},
            ],
            onlineOptions: [
                {title: 'All', value: null, selected: true},
                {title: 'Online', value: true, selected: false},
                {title: 'Offline', value: false, selected: false},
            ],
            proxyTypes: [
                {title: 'Any', value: null, selected: true}
            ],
        }
    },
    methods: {
        setTypeToFilter(value) {
            this.proxyTypes.forEach((option) => {
                option.selected = option.value === value;
            });

            this.$emit('setToFilter', {
                filter: 'type', value: value
            })
        },
        setOnlineToFilter(value) {
            this.onlineOptions.forEach((option) => {
                option.selected = option.value === value;
            });

            this.$emit('setToFilter', {
                filter: 'is_online', value: value
            })
        },
        setPaidToFilter(value) {
            this.paidOptions.forEach((option) => {
                option.selected = option.value === value;
            });

            this.$emit('setToFilter', {
                filter: 'is_paid', value: value
            })
        },
        getTypes() {
            (new ProxyService())
                .getProxyTypes()
                .then(res => {
                    const types = res.data.types;
                    types.forEach((type) => {
                        this.proxyTypes.push({title: type.name, value: type.id, selected: false})
                    })
                })
                .catch(err => {
                    console.error(err.response.data);
                })
        },
         priceMask(evt) {
            evt = (evt) ? evt : window.event;
            const charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        },

    },
    beforeMount() {
        this.getTypes();
    }
}
</script>

<style scoped>

.options-form {
    gap: 5px;

    .options-form-box {
        &:nth-child(1) {
            max-width: 100px;
        }
        &:nth-child(2) {
            max-width: 80px;
        }
        &:nth-child(3) {
            max-width: 80px;
        }
        &:nth-child(4) {
            max-width: 50px;
        }
        &:nth-child(5) {
            max-width: 50px;
        }
        &:nth-child(6) {
            max-width: 50px;
        }
        &:nth-child(7) {
            max-width: 40px;
        }
        &:nth-child(8) {
            max-width: 250px;
        }
        &:nth-child(9) {
            max-width: 80px;
        }
        &:nth-child(10) {
            max-width: 100px;
        }
        &:nth-child(11) {
            max-width: 100px;
        }
        &:nth-child(12) {
            max-width: 60px;
        }
    }
}

</style>
