import {MAIN_LAYOUT} from '../base';
import type { AppRouteRecordRaw } from '../types';

const Home: AppRouteRecordRaw = {
    path: '/home',
    name: 'root',
    component: MAIN_LAYOUT,
    children: [
        {
            path: '',
            name: 'home',
            component: () => import('@/views/home/Index.vue'),
            meta: {
                title: 'Home',
            },
        }
    ],
};

export default Home;
