import ApiService from './ApiService.js'
import {authService} from "./AuthService.js";
class PaymentService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();

        return {...authHeader};
    }

    async topUpBalance() {
        return await super.post('/user/topup', this.getBaseHeaders())
    }

    async getMyPayments() {
        return await super.get('/my_payments', this.getBaseHeaders() )
    }
    }


export default PaymentService;
