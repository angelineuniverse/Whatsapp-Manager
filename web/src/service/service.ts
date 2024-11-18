import axios from 'axios';
import { redirect } from "react-router-dom";
import { getCookie, removeCookie } from 'typescript-cookie';
import { NotificationService } from '@angelineuniverse/design';
const client = axios.create({
    baseURL: process.env.REACT_APP_BASE_URL,
    headers: {
        'Authorization': 'Bearer '+getCookie('LOG')
    }
});

client.interceptors.request.use((config) => {
    const token = getCookie('LOG');
    if (token) {
        config.headers['Authorization'] = 'Bearer '+token;
    }
    return config;
});

client.interceptors.response.use(
    async function (response) {
        const res = await response.data;
        if (res?.notification) {
            NotificationService.show({
                key: 'success',
                position: 'top-right',
                theme: 'success',
                title: res?.notification?.title,
                body: res?.notification?.body,
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
            title: err?.response?.data?.notification?.title ?? 'Oops... something wrong !',
            body: err?.response?.data?.notification?.body ?? err?.response?.data?.message,
            duration: 5000
        })
        if (err?.status === 401) {
            removeCookie('LOG');
            return redirect('/auth');
        }
        return Promise.reject(new Error(err));
    }
);

export default client;