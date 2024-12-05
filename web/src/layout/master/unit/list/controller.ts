import client from "../../../../service/service";

export function create() {
    return client.get('/unit/create');
}
export function edit(id: string|undefined) {
    return client.get('/unit/'+id+'/edit');
}
export function tables(params?: object) {
    return client.get('/unit', {
        params: params
    });
}
export function store(data: any) {
    return client.post("/unit", data)
}
export function update(id: string|undefined,data: any) {
    return client.post("/unit/" + id, data)
}
export function activated(id: string|undefined,data: any) {
    return client.put("/unit/" + id, data)
}
export function remove(id: any) {
    return client.delete("/unit/"+id)
}