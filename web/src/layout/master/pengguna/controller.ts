import client from "../../../service/service";

export function create() {
    return client.get('/pengguna/create');
}
export function edit(id: string|undefined) {
    return client.get('/pengguna/'+id+'/edit');
}
export function tables(params?: object) {
    return client.get('/pengguna', {
        params: params
    });
}
export function store(data: any) {
    return client.post("/pengguna", data)
}
export function update(id: string|undefined,data: any) {
    return client.post("/pengguna/" + id, data)
}
export function activated(id: string|undefined,data: any) {
    return client.put("/pengguna/" + id, data)
}
export function remove(id: any) {
    return client.delete("/pengguna/"+id)
}