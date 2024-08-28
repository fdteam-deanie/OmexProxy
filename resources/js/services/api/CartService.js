import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class CartService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async getCart() {
        return await super.get( '/cart', this.getBaseHeaders());
    }

    async addToCart(proxyId) {
        this.update({ proxyId: proxyId })
        return await super.post('/cart/add', this.getBaseHeaders())
    }

    async updateCartItem(proxyId, days) {
        this.update({ proxyId: proxyId, days: days })
        return await super.post('/cart/update', this.getBaseHeaders())
    }

    async confirmOrder(days = 1) {
        this.update({ days: days })
        return await super.post('/catalog/order', this.getBaseHeaders())
    }

    async removeFromCart(proxyId){
        this.update({ proxyId: proxyId })
        return await super.delete('/cart/remove', this.getBaseHeaders())
    }
}

export default CartService
