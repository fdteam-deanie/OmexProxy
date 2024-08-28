import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class OrderService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async quickBuy(proxy, days) {
        this.update({
            proxy_id: proxy.id,
            days: days
        });
        return await super.post( `/orders/quick-buy`, this.getBaseHeaders());
    }

    async renewRental(proxy, days) {
        this.update({
            proxy_id: proxy.id,
            days: days
        });
        return await super.post( `/orders/renew-rental`, this.getBaseHeaders());
    }
}

export default OrderService
