import client from "../../service/service"

export function login(data: any) {
    return client.post('/users/login', data);
}