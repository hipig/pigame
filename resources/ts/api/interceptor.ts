import axios from 'axios';
import type { AxiosResponse, InternalAxiosRequestConfig } from 'axios';
import { useUserStore } from '@/store';
import { useToast } from "primevue/usetoast";
import toasteventbus from 'primevue/toasteventbus'

if (import.meta.env.VITE_API_BASE_URL) {
    axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL;
}

axios.interceptors.request.use(
    (config: InternalAxiosRequestConfig) => {
        const userStore = useUserStore();
        const token = userStore.token;
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        const socketId = window.Echo.socketId();
        if (socketId) {
            config.headers['X-Socket-ID'] = socketId;
        }
        return config;
    },
    (error) => {
        // do something
        return Promise.reject(error);
    }
);
// add response interceptors
axios.interceptors.response.use(
    (response: AxiosResponse) => {
        return response.data;
    },
    async (error) => {
        // const toast = useToast();
        const userStore = useUserStore();
        const response = error.response;
        const showToast = (detail: string, summary = '错误提示') => {
            toasteventbus.emit('add', { severity: 'error', summary, detail, life: 2000 })
        }
        switch (response?.status) {
            case 401:
                await userStore.clearToken();
                // window.location.reload();
                break;
            case 403:
            case 404:
            case 500:
                showToast(response.data.message || '');
                break;
            case 422:
                showToast(response.data.message || '');
                break;
        }

        return Promise.reject(response);
    }
);
