import axios from 'axios';

export function authorizations(data) {
    return axios.post('authorizations', data);
}

export function me() {
    return axios.get('me');
}

export function logout() {
    return axios.delete('authorizations');

}
