import client from "../../service/service"

export function profile() {
    return client.get('profile');
}
export function profile_log(params?: object) {
    return client.get('profile/log', {
        params: params
    });
}