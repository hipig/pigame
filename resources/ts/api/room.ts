import axios from 'axios';

export function rooms(params = {}) {
    return axios.get('rooms', {params});
}

export function storeRooms(data) {
    return axios.post('rooms', data);
}

export function updateRooms(roomId, data) {
    return axios.put('rooms/' + roomId, data);
}

export function showRooms(roomId) {
    return axios.get('rooms/' + roomId);
}

export function joinRooms(roomId, data) {
    return axios.post('rooms/' + roomId + '/join', data);
}

export function leaveRooms(roomId) {
    return axios.post('rooms/' + roomId + '/leave');
}