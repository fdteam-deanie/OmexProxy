import { createStore } from 'vuex'

const store = createStore({
  state() {
    return {
      user: {
        id: undefined,
        name: '',
        socks5Auth: {
          username: '',
          password: ''
        },
        token: '',
        loggedIn: false,
        balance: 0,
        isBlock: undefined,
      },
      cart: {
        cartItems: {},
        cartItemsIDS: [],
        cartTotal: 0,
      },
      rentPeriods: [],
      userBalance: 0,
      userBonusCredits: 0,
      userCredits: 0,
      unlimitedSubscription: null,
      unlimitedSubscriptionPrice: 0,
    }
  },
  getters: {
    getUserId(state) {
      return state.user.id
    },
    socks5Auth(state) {
      return state.user.socks5Auth
    },
    cart(state) {
      return state.cart;
    },
    cartItems(state) {
      return state.cart.cartItems;
    },
    cartTotal(state) {
      return state.cart.cartTotal;
    },
    isLogged(state) {
      return state.user.loggedIn
    },
    loggedIn(state) {
      return state.user.loggedIn
    },
    userBalance(state) {
      return state.user.balance;
    },
    userBonusCredits(state) {
      return state.userBonusCredits;
    },
    userCredits(state) {
      return state.userCredits;
    },
    userBlock(state) {
      return state.user.isBlock;
    },
    userData(state) {
      return {
        id: state.user.id,
        username: state.user.name,
        token: state.user.token,
        loggedIn: state.user.loggedIn,
        isBlock: state.user.isBlock,
      }
    },
    rentPeriods(state) {
      return state.rentPeriods;
    },
    hasActiveUnlimitedSubscription(state) {
      return state.unlimitedSubscription !== null && state.unlimitedSubscription !== undefined && state.unlimitedSubscription !== '';
    },
    unlimitedSubscription(state) {
      return state.unlimitedSubscription;
    },
    unlimitedSubscriptionPrice(state) {
      return state.unlimitedSubscriptionPrice;
    },
  },
  mutations: {
    resetUserData(state) {
      state.user = {
        id: undefined,
        name: '',
        socks5Auth: {
          username: '',
          password: ''
        },
        token: '',
        loggedIn: false,
        balance: 0,
        isBlock: 0
      }
    },
    setSocks5Auth(state, socks5AuthData) {
      state.user.socks5Auth = socks5AuthData
    },
    setCart(state, cartData) {
      state.cart.cartItems = cartData.cartItems;
      state.cart.cartTotal = cartData.cartTotal
    },
    setUserBalance(state, balance) {
      state.user.balance = parseFloat(balance);
    },
    setUserBonusCredits(state, bonusCredits) {
      state.userBonusCredits = parseFloat(bonusCredits);
    },
    setUserCredits(state, credits) {
      state.userCredits = parseFloat(credits);
    },
    setUserData(state, userData) {
      state.user.id = userData.id;
      state.user.name = userData.username;
      state.user.isBlock = userData.as_block;
    },
    setLoggedIn(state, loggedIn) {
      state.user.loggedIn = loggedIn;
    },
    setRentPeriods(state, rentPeriods) {
      state.rentPeriods = rentPeriods;
    },
    setUnlimitedSubscriptionData(state, unlimitedSubscriptionData) {
      state.unlimitedSubscription = unlimitedSubscriptionData.unlimitedSubscription;
      state.unlimitedSubscriptionPrice = unlimitedSubscriptionData.unlimitedSubscriptionPrice;
    },
  }
})

export default store;
