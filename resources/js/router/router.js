import { createRouter, createWebHistory } from 'vue-router';

/* Components */
import UserLogin from '../components/User/UserLogin.vue';
import UserRegister from '../components/User/UserRegister.vue';
import UserRecovery from '../components/User/UserRecovery.vue';
import UserReset from '../components/User/UserReset.vue';
import UserBalance from '../components/User/UserBalance.vue';
import UserSocks from "../components/User/UserSocks.vue";
import UserAccount from '../components/User/UserAccount.vue';

import ProxyCatalog from '../views/ProxyCatalog.vue';
import MyProxies from '../views/MyProxies.vue';
import MyPayments from '../views/MyPayments.vue';
import AddPayment from "../views/AddPayment.vue";
import SuccessPayment from '../views/payments/Success.vue';
import CancelPayment from '../views/payments/Cancel.vue';
import History from "../views/History.vue";
import Home from "../views/Home.vue";
import News from "../views/News/NewsList.vue";
import NewsArticle from "../views/News/NewsArticle.vue";
import SupportList from "../views/Support/SupportList.vue";
import SupportTicket from "../views/Support/SupportTicket.vue";

import { auth, guest, checkUserStatus } from './middlewares'
import Launching from "../views/Launching.vue";


const router = createRouter({

  history: createWebHistory(),
  routes: [

    // {
    //     path: '/',
    //     name: 'Home',
    //     component: Home,
    //     meta: {
    //         middleware: guest
    //     },
    // },
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: {
        middleware: guest
      },
    },
    {
      path: '/news',
      name: 'News',
      component: News,
      meta: {
        middleware: auth
      },
    },
    {
      path: '/news/:id',
      name: 'NewsArticle',
      component: NewsArticle,
      meta: {
        middleware: auth
      },
    },
    {
      path: '/support',
      name: 'SupportList',
      component: SupportList,
      meta: {
        middleware: auth
      },
    },
    {
      path: '/support/:id',
      name: 'SupportTicket',
      component: SupportTicket,
      meta: {
        middleware: auth
      },
    },
    {
      path: '/user/login',
      name: 'UserLogin',
      component: UserLogin,
      meta: {
        middleware: guest
      },
    },
    {
      path: '/user/register',
      name: 'UserRegister',
      component: UserRegister,
      meta: {
        middleware: guest
      },
    },
    {
      path: '/user/recovery',
      name: 'UserRecovery',
      component: UserRecovery,
      meta: {
        middleware: guest
      },
    },
    {
      path: '/password/reset/:token',
      name: 'UserReset',
      component: UserReset,
      meta: {
        middleware: guest
      },
    },
    {
      path: '/user/proxy',
      name: 'ProxyCatalog',
      component: ProxyCatalog,
      meta: {
        middleware: auth,
        middleware: checkUserStatus
      },
    },
    {
      path: '/user/history',
      name: 'History',
      component: History,
      meta: {
        middleware: auth,
        middleware: checkUserStatus
      },
    },
    {
      path: '/user/socks',
      name: 'UserSocks',
      component: UserSocks,
      meta: {
        middleware: auth
      },
    },
    {
      path: '/user/account',
      name: 'UserAccount',
      component: UserAccount,
      meta: {
        middleware: auth
      },
    },
    {
      path: '/user/my',
      name: 'MyProxies',
      component: MyProxies,
      meta: {
        middleware: auth,
        middleware: checkUserStatus
      },
    },
    {
      path: '/user/payments',
      name: 'MyPayments',
      component: MyPayments,
      meta: {
        middleware: auth,
        middleware: checkUserStatus
      },
    },
    {
      path: '/payments/add',
      name: 'AddPayment',
      component: AddPayment,
      meta: {
        middleware: auth,
        middleware: checkUserStatus
      },
    },
    {
      path: '/payments/success',
      name: 'SuccessPayment',
      component: SuccessPayment,
      meta: {
        middleware: guest
      },
    },
    {
      path: '/payments/cancel',
      name: 'CancelPayment',
      component: CancelPayment,
      meta: {
        middleware: guest
      },
    },
    // {
    // 	path: '/user/balance',
    // 	name: 'UserBalance',
    // 	component: UserBalance,
    //     meta: {
    //         middleware: auth
    //     },
    // }

  ]

})

export default router
