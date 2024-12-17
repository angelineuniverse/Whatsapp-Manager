import client from "../service/service";

export function MenuIndex() {
    return client.get('/users');
}

export function logoutAccount() {
    return client.delete('/users/logout')
}