import { eloquentWith } from "../../../utils/eloquent.ts";
import connection from "../../../connection/database.ts";
import { MMenuTab } from "../Model/MMenuTab.ts";

export function index(req: any, res: any) {
    connection.query(`select * from m_menu_tab a where a.isactive = ?`, [1], (err, result: any) => {
        if (err) throw err;
        const response = result as Array<MMenuTab>;
        const respon = response?.map((item) => ({
            ...item,
            show: false
        }));
        const output = eloquentWith(respon, "id", "parent_id", "child");
        return res.json(output);
    })
}