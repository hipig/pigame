import type {Router, LocationQueryRaw} from 'vue-router';
import {useUserStore} from '@/store';

export default function setupUserLoginGuard(router: Router) {
    router.beforeEach(async (to, from) => {
        const userStore = useUserStore();

        if (userStore.token) {
            if (!userStore.userInfo.id) {
                await userStore.getUserInfo();
            }
            return true;
        }
    });

    router.afterEach(() => {
        //
    })
}
