import client from "../../../../service/service";

export function create() {
    return client.get('/unittype/create');
}
export function edit(id: string|undefined) {
    return client.get('/unittype/'+id+'/edit');
}
export function tables(params?: object) {
    return client.get('/unittype', {
        params: params
    });
}
export function store(data: any) {
    return client.post("/unittype", data)
}
export function update(id: string|undefined,data: any) {
    return client.post("/unittype/" + id, data)
}
export function activated(id: string|undefined,data: any) {
    return client.put("/unittype/" + id, data)
}
export function remove(id: any) {
    return client.delete("/unittype/"+id)
}