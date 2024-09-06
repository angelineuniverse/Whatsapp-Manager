import axios from 'axios';
import { getCookie } from 'typescript-cookie';
const client = axios.create({
    baseURL: 'http://localhost:8000',
    headers: {
        Authorization: 'Bearer '
    }
});

client.interceptors.request.use((config) => {
    const token = getCookie('LOG');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

client.interceptors.response.use(
    async function (response) {
        const res = await response.data;
        return res;
    },
    function (err) {
        Promise.reject(new Error(err));
    }
);

export default client;