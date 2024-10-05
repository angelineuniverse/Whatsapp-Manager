import axios from 'axios';
import { getCookie } from 'typescript-cookie';
import { NotificationService } from '@angelineuniverse/design';
const client = axios.create({
    baseURL: 'http://localhost:8000',
    headers: {
        'x-auth-token': getCookie('LOG')
    }
});

client.interceptors.request.use((config) => {
    const token = getCookie('LOG');
    if (token) {
        config.headers['x-auth-token'] = token;
    }
    return config;
});

client.interceptors.response.use(
    async function (response) {
        const res = await response.data;
        if (res?.notif) {
            NotificationService.show({
                key: 'error',
                position: 'top-right',
                theme: 'success',
                title: res?.notif?.title,
                body: res?.notif?.body,
                duration: 5000
            })
        }
        return response;
    },
    function (err) {
        // if (err?.status === 401) {
            NotificationService.show({
                key: 'error',
                position: 'top-right',
                theme: 'error',
                title: err?.response?.data?.notif?.title,
                body: err?.response?.data?.notif?.body,
                duration: 5000
            })
        // }
        return Promise.reject(new Error(err));
    }
);

export default client;