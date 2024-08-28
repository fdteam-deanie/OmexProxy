import ApiService from "./ApiService.js";
import { authService } from "./AuthService.js";
import axios from "axios";

class WalletService extends ApiService {
  constructor() {
    super();
  }

  getBaseHeaders() {
    const authHeader = authService.getAuthHeader();
    return { ...authHeader };
  }

  async getWallets() {
    return await super.post('/wallets', this.getBaseHeaders())
  }

  async recheckBalance(coinCode) {
    return await super.post('/wallets/balance-recheck/' + coinCode, this.getBaseHeaders())
  }

  async getCoinRates() {
    return await axios.get('https://api.coingecko.com/api/v3/simple/price?ids=bitcoin%2Clitecoin&vs_currencies=usd')
  }

  async getStripeSession(amount, userId) {
    return await super.get('/wallets/get-stripe-session/' + amount + '/' + userId,  this.getBaseHeaders());
  }
}

export default WalletService;
