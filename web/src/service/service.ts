import axios from 'axios';
const client = axios.create({
    baseURL: '',
    headers: {
        Authorization: 'Bearer '
    }
});

client.interceptors.request.use((config) => {
    const token = false; // Ambil dari cookie;
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