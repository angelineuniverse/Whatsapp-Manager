import client from "../../../service/service";

export function create() {
    return client.get('/roles/create');
}
export function edit(id: string|undefined) {
    return client.get('/roles/'+id+'/edit');
}
export function tables() {
    return client.get('/roles');
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