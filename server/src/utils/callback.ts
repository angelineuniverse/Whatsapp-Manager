export function responses(message: string, data: any, notif?: any) {
    return {
        "message": message,
        "data": data,
        "notif": notif,
    }
}