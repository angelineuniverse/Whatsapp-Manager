import connection from "../../../connection/database.ts";

export interface MCodeTab {
    id: number;
    preffix: string;
    start: number;
    length: number;
    year: number;
    description?: string;
}

export function generateCode(preffix: string) {
    return new Promise((resolve, reject) => {
        connection.query(`select * from m_code_tab a where a.preffix = ?`, [preffix], (err, res: any) => {
            if (err) reject(err);
            const result = res[0] as MCodeTab;
            if (result.year != new Date().getFullYear() % 100) {
                connection.query('update m_code_tab set year = ?, start = ?', [new Date().getFullYear() % 100, 1]);
            }
            let start = result.start.toString();
            connection.query('update m_code_tab set start = ?', [result.start + 1]);
            resolve(result.preffix + result.year + start.padStart(result.length, '0'));
        })
    });
}