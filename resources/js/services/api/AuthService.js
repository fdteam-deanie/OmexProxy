import ApiService from "./ApiService.js";

class AuthService extends ApiService {

    #localStorageItemName = 'at';
    #tokenPrefix = 'Bearer ';

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async login() {
        return await super.post('/auth/login');
    }

    async register() {
        return await super.post('/auth/register');
    }

    async logout() {
        return await super.post('/auth/logout', this.getBaseHeaders());
    }

    async recovery() {
        return await super.post('/auth/recovery');
    }

    async resetPasswordByToken(token){
        return await super.post('/auth/reset-password/' + token);

    }

    async changePassword(){
        return await super.post('/auth/password', this.getBaseHeaders());
    }

    getAuthHeader() {
        return {
            Authorization: this.#tokenPrefix + this.getAuthToken()
        }
    }

    getAuthToken() {
        return localStorage.getItem(this.#localStorageItemName);
    }

    async check() {
        return await super.post('/auth/token/check', {...this.getAuthHeader()})

    }

}

export const authService = new AuthService;

export default AuthService;
