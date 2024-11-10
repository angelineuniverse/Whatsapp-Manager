import client from "../../../service/service";

export function form() {
    return client.get('/user/form');
}
export function tables() {
    return client.get('/users');
}