import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class HistoryService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async getProxies(data) {
        this.update({
            ...data
        });
        return await super.post( '/history', this.getBaseHeaders());
    }
}

export default HistoryService
