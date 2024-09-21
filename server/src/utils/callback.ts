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
        "code": 200,
        "notif": notif,
    }
}

