export interface Notifikasi {
    position?: string;
    theme: string;
    title: string;
    body: string;
}

export function responses(message: string, data: any, code?: number, notif?: Notifikasi) {
    return {
        "message": message,
        "data": data,
        "code": code ?? 200,
        "notif": notif,
    }
}

export function responsesTable(message: string, column: Array<any> ,data: any, code?: number) {
    return {
        "message": message,
        "column": column,
        "data": data,
        "code": code ?? 200,
    }
}

