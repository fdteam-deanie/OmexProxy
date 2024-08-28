import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class HistoryService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async getActiveUnlimitedSubscription() {
        return await super.get( '/unlimited-subscriptions/active', this.getBaseHeaders());
    }

    async getUnlimitedSubscriptionPrice() {
        return await super.get( '/unlimited-subscriptions/price', this.getBaseHeaders());
    }

    async subscribeUnlimitedSubscription() {
        return await super.post( '/unlimited-subscriptions/subscribe', this.getBaseHeaders());
    }
}

export default HistoryService
