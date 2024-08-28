import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class ProxyService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async getProxyDetailInfo(proxyId) {
        return await super.get( `/proxies/${proxyId}/detail`, this.getBaseHeaders());
    }

    async getProxyTypes() {
        return await super.get( `/proxies/types`, this.getBaseHeaders());
    }

    async refundProxy(proxyId) {
        return await super.post('/proxies/refund/' + proxyId, this.getBaseHeaders())
    }

    async getRentPeriods() {
        return await super.get( `/rent-periods`, this.getBaseHeaders());
    }
}

export default ProxyService
