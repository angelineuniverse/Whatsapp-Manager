import client from "../service/service";

export function MenuIndex() {
    return client.get('/menu');
}

export function logoutAccount() {
    return client.delete('/users/logout')
}