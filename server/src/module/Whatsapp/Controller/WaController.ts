import WhatsAppConnection from "../index.ts";

export function started(req: any, res: any) {
    return new WhatsAppConnection().started("angeline", res);
}