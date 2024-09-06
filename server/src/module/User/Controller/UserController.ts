import connection from '../../../connection/database.ts';
import { Auth } from '../../../../provider/Auth.ts';
import moment from 'moment';
export async function index(req: any, res: any) {
    connection.query('select * from m_user_tab where isactive = 1 and m_company_tab_id = ?',[req.query.company_id], (err, result) => {
        if (err) throw err;
        res.json(result);
    });
}
export async function store(req: any, res: any) {
    const password = new Auth().hashing(req.body.password);
    const maps = {
        m_company_tab_id: req.body.m_company_tab_id,
        token: req.body.token,
        email: req.body.email,
        password: password,
        contact: req.body.contact,
        m_status_tab_id: req.body.m_status_tab_id,
        m_access_tab_id: req.body.m_access_tab_id,
        isactive: req.body.isactive,
        created_at: moment().format('YYYY-MM-DD HH:mm:ss'),
        updated_at: moment().format('YYYY-MM-DD HH:mm:ss'),
    };
    connection.query(`
            INSERT INTO m_user_tab
            (m_company_tab_id,token,email,password,contact,m_status_tab_id,m_access_tab_id,isactive,created_at,updated_at)
            VALUES
            (?,?,?,?,?,?,?,?,?,?)`,
        [maps.m_company_tab_id,maps.token ?? null, maps.email, maps.password, maps.contact, 1, maps.m_access_tab_id, 0, maps.created_at, maps.updated_at]
        , (err, result) => {
        if (err) throw err;
        res.json({
            'error_code': null,
            'message': 'CREATE ACCOUNT SUCCESS',
            'data': result
        });
    });
}
export async function login(req: any, res: any) {
    const auth: boolean = await new Auth().attempt(req.body);
    if (auth) {
        connection.query(
            `SELECT * FROM m_user_tab a WHERE a.email=?`, [req.body.email], async (err, result: any) => {
                if (err) throw err;
                const token = await new Auth().generateToken(result[0].id, process.env.TOKEN_KEY);
                return res.json(token)
            }
        )
    }
}
export async function show(req: any, res: any) {
    const user = await new Auth().user(req);
    return res.json(user);
}

// INI NANTI LIAT KONDISI
export async function update(req: any, res: any) {
    connection.query(`UPDATE m_user_tab SET where id = ${req.body.id}`, (err, result) => {
        if (err) throw err;
        res.json({
            'error_code': null,
            'message': 'USER UPDATED',
            'data': result
        });
    });
}
export async function destroy(req: any, res: any) {
    connection.query(`DELETE FROM m_user_tab where id = ${req.params.id}`, (err, result) => {
        if (err) throw err;
        res.json({
            'error_code': null,
            'message': 'USER DELETED',
            'data': result
        });
    });
}