import client from "../../service/service"

export function login(data: any) {
    return client.post('/user/login', data);
}