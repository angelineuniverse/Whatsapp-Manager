import axios from 'axios';
import { getCookie } from 'typescript-cookie';
import { NotificationService } from '@angelineuniverse/design';
const client = axios.create({
    baseURL: process.env.REACT_APP_BASE_URL,
    headers: {
        'Authorization': getCookie('LOG')
    }
});

client.interceptors.request.use((config) => {
    const token = getCookie('LOG');
    if (token) {
        config.headers['Authorization'] = token;
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
        NotificationService.show({
            key: 'error',
            position: 'top-right',
            theme: 'error',
            title: err?.response?.data?.notif?.title ?? 'Oops... something wrong !',
            body: err?.response?.data?.notif?.body ?? err?.response?.data?.message,
            duration: 5000
        })
        return Promise.reject(new Error(err));
    }
);

export default client;