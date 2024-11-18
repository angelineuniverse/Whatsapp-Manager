import client from "../../../service/service";

export function create() {
    return client.get('/users/create');
}
export function edit(id: string|undefined) {
    return client.get('/users/'+id+'/edit');
}
export function tables(params?: object) {
    return client.get('/users', {
        params: params
    });
}
export function store(data: any) {
    return client.post("/users", data)
}
export function update(id: string|undefined,data: any) {
    return client.post("/users/" + id, data)
}
export function activated(id: string|undefined,data: any) {
    return client.put("/users/" + id, data)
}
export function remove(id: any) {
    return client.delete("/users/"+id)
}