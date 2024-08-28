import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class CatalogService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();

        return {...authHeader};
    }
    
    async getCatalog() {
        return await super.post('/catalog', this.getBaseHeaders())
    }

    async getMyCatalog() {
        return await super.post('/my_catalog', this.getBaseHeaders())
    }
}

export default CatalogService;
