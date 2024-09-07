import {MAIN_LAYOUT} from '../base';
import type { AppRouteRecordRaw } from '../types';

const Game: AppRouteRecordRaw = {
    path: '/g',
    name: 'game.root',
    component: MAIN_LAYOUT,
    children: [
        {
            path: ':gameKey',
            name: 'game.detail',
            component: () => import('@/views/game/Index.vue'),
            meta: {
                title: 'Game',
            },
        },
        {
            path: ':gameKey/:roomCode',
            name: 'room.detail',
            component: () => import('@/views/game/Room.vue'),
            meta: {
                title: 'Game',
            },
        }
    ],
};

export default Game;
