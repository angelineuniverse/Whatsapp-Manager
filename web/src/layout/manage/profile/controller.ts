import client from "../../../service/service";

export function form() {
    return client.get('/user/form');
}