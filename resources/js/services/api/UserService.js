import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class UserService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async getBalance(){
        return await super.get('/user/balance', this.getBaseHeaders())
    }

    async getSocks5Credentials(){
        return await super.get('/user/socks5', this.getBaseHeaders())
    }

    async setSocks5Credentials(){
        return await super.post('/user/socks5', this.getBaseHeaders())
    }

}

export default UserService;
