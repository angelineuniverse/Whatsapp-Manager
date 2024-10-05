import connection from "../../../connection/database.ts";
import { Auth } from "../../../../provider/Auth.ts";
import { MUserTab } from "module/User/Model/MUserTab.ts";

export async function index(req: any, res: any) {
    const user = await new Auth().user(req) as MUserTab;
    connection.query(
        `select 
            a.*,
            b.parent_id,
            b.path,
            b.title,
            b.icon,
            b.isactive
        from m_menu_access_tab a
        left join m_menu_tab b on a.m_menu_tab_id = b.id
        where a.m_access_tab_id = ?`,
        user.m_access_tab_id, (err, result: Array<any>) => {
            connection.query(`
                select
                    *
                from m_menu_tab a
                where a.parent_id is not null
            `, (err, resultchild: Array<any>) => {
                const output = result.map((item) => ({
                    ...item,
                    child: resultchild.filter((x) => x.parent_id == item.id) ?? null
                }));

                return res.json(output);
            })
        })
}