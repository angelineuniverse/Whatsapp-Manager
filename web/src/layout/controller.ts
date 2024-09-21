import client from "../service/service";

export function MenuIndex() {
    return client.get('/menu');
}