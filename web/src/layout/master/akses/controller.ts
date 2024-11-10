import client from "../../../service/service";

export function create() {
    return client.get('/access/create');
}
export function edit(id: string|undefined) {
    return client.get('/access/'+id+'/edit');
}
export function tables() {
    return client.get('/access');
}
export function add(data: any) {
    return client.post("/access", data)
}
export function update(id: string|undefined,data: any) {
    return client.put("/access/" + id, data)
}
export function remove(id: any) {
    return client.delete("/access/"+id)
}