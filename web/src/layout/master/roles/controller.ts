import client from "../../../service/service";

export function create() {
    return client.get('/roles/create');
}
export function edit(id: string|undefined) {
    return client.get('/roles/'+id+'/edit');
}
export function show(id: string|undefined, params?: any) {
    return client.get('/roles/' + id, {
        params: params
    });
}
export function tables(params?: object) {
    return client.get('/roles', {
        params: params
    });
}
export function add(data: any) {
    return client.post("/roles", data)
}
export function update(id: string|undefined,data: any) {
    return client.put("/roles/" + id, data)
}
export function remove(id: any) {
    return client.delete("/roles/"+id)
}