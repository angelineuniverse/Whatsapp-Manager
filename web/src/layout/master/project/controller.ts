import client from "../../../service/service";

export function create() {
    return client.get('/project/create');
}
export function edit(id: string|undefined) {
    return client.get('/project/'+id+'/edit');
}
export function tables(params?: object) {
    return client.get('/project', {
        params: params
    });
}
export function store(data: any) {
    return client.post("/project", data)
}
export function update(id: string|undefined,data: any) {
    return client.post("/project/" + id, data)
}
export function activated(id: string|undefined,data: any) {
    return client.put("/project/" + id, data)
}
export function remove(id: any) {
    return client.delete("/project/"+id)
}