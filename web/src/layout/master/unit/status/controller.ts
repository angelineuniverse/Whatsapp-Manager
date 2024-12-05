import client from "../../../../service/service";

export function create() {
    return client.get('/unitstatus/create');
}
export function edit(id: string|undefined) {
    return client.get('/unitstatus/'+id+'/edit');
}
export function tables(params?: object) {
    return client.get('/unitstatus', {
        params: params
    });
}
export function store(data: any) {
    return client.post("/unitstatus", data)
}
export function update(id: string|undefined,data: any) {
    return client.post("/unitstatus/" + id, data)
}
export function activated(id: string|undefined,data: any) {
    return client.put("/unitstatus/" + id, data)
}
export function remove(id: any) {
    return client.delete("/unitstatus/"+id)
}