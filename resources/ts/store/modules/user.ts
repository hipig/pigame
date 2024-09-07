import {defineStore} from 'pinia';
import {
    authorizations,
    logout as userLogout,
    me,
} from '@/api/authorization';
import {removeRouteListener} from '@/utils/routeListener';

// @ts-ignore
const useUserStore = defineStore('user', {
    state: () => ({
        token: undefined,
        userInfo: {
            id: undefined,
            name: undefined,
            email: undefined,
        }
    }),
    persist: {
        paths: [
            'token'
        ]
    },
    actions: {
        async getUserInfo() {
            this.userInfo = await me();
        },
        // Login
        async login(loginForm) {
            try {
                const res = await authorizations(loginForm);
                this.setToken(res.access_token);
            } catch (err) {
                this.clearToken();
                throw err;
            }
        },
        // Logout
        async logout() {
            try {
                await userLogout();
            } finally {
                this.resetInfo();
                removeRouteListener();
            }
        },
        setToken(token: string) {
            this.token = token;
        },
        clearToken() {
            this.setToken(undefined);
        },
        resetInfo() {
            this.$reset();
        }
    }
});

export default useUserStore;

export type { userInfoType }
