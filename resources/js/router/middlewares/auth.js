import { authService } from "../../services/api/AuthService.js";

const  checkToken = async ( store ) => {
    const user = store.getters.userData;

    if (user.loggedIn)
        return true;

    const token = user.token || authService.getAuthToken();

    return authService.check(token);
};

export default async function auth ({ next, store }){
    const user = store.getters.userData;

    if(user.loggedIn)
        return next();

    const token = user.token || authService.getAuthToken();

    if(!token) {
        next({ name: 'Home' });

    } else {
        try {
            const response = await checkToken(store);

            if (response.data) {
                store.commit('setUserData', {
                    loggedIn: true,
                    ...response.data
                });
                next();
            } else {
                console.log(1)
                store.commit('resetUserData');
                localStorage.removeItem('at');
                next({ name: 'Home' });
            }

        } catch (err) {
            console.error(err.response.data);
            store.commit('resetUserData');
            localStorage.removeItem('at');
            next({ name: 'Home' });
        }
    }
}
