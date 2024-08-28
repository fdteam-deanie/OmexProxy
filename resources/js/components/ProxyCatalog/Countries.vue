<template>
    <section class="main__countries countries" >
        <div class="big-container">
            <div class="countries__inner">
                <div class="countries__tubs countries-tubs" data-aos="zoom-in">
                    <button
                        v-for="continent in continents"
                        :key="continent.id"
                        class="countries-tubs__button"
                        :class=" continent.id === continentId ? 'countries-tubs__button--active' : ''"
                        @click="setSelectedContinent(continent.id)"
                    >
                        {{ continent.name }}
                        <span>{{ continent.proxies_cnt }}</span>
                    </button>
                </div>
                <div class="countries__wrapper countries__wrapper--active" data-aos="fade-down">
                    <ul class="countries__list countries-list">
                        <li
                            class="countries-list__item"
                            v-for="country in countries"
                            :key="country.id"
                            :class=" country.id === countryId ? 'countries-list__item--active' : ''"

                            @click="setSelectedCountry(country.id)"
                        >
                            <img class="countries-list__flag" :src="country.flag" alt="flag">
                            <p class="countries-list__text">
                                {{ country.name }} - {{ country.proxies_cnt }}
                            </p>
                            <input class="countries-list__item-radio" type="radio">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import GeoService from "../../services/api/GeoService.js";

export default {
    name: "GeoComponent",
    props: [ 'continentId', 'countryId' ],
    data() {
        return {
            continents: [],
            countries: [],
        }
    },
    methods: {
        setSelectedContinent(id) {
            this.$emit('setContinentId',id )
            this.$emit('setCountryId', null )
        },
        getContinents() {
            (new GeoService())
                .getContinents()
                .then(res => {
                    const data = res.data;
                    if(data.status === 'success') {
                        this.continents = data.continents;
                    }
                })
                .catch(err => {
                    console.error(err.response.data)
                })
        },
        setSelectedCountry(id){
            this.$emit('setCountryId', id )
        },
        getCountries() {
            (new GeoService())
                .getCountries(this.continentId)
                .then(res => {
                    const data = res.data;
                    if(data.status === 'success') {
                        this.countries = data.countries;
                    }
                })
                .catch(err => {
                    console.error(err.response.data)
                })
        },
    },
    beforeMount() {
        this.getContinents();
        this.getCountries();
    },
    watch: {
        continentId() {
            this.getCountries()
        }
    }
}
</script>

<style scoped>

</style>
