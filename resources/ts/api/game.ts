import axios from 'axios';

export function gameCategories() {
    return axios.get('game-categories');
}

export function showGames(gameKey: string) {
    return axios.get(`games/${gameKey}`);
}