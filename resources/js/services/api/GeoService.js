import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class GeoService extends ApiService {
    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async getContinents() {
        return await super.get( '/geo/continents', this.getBaseHeaders());
    }

    async getCountries(continentId) {
        let url = '/geo/countries';
        if(undefined !== continentId) {
            url += `/${continentId}`;
        }

        return await super.get( url, this.getBaseHeaders());
    }
}

export default GeoService;
