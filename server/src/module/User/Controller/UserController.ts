import connection from '../../../connection/database.ts';
import { Auth } from '../../../../provider/Auth.ts';
import moment from 'moment';
export async function index(req: any, res: any) {
    const auth = new Auth().hashing(req.body.password);
    res.json(auth);
    // connection.query('select * from m_user_tab where isactive = 1', (err, result) => {
    //     if (err) throw err;
    //     res.json();
    // });
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
            ('${maps.m_company_tab_id}','${maps.token}','${maps.email}','${maps.password}','${maps.contact}','${maps.m_status_tab_id}','${maps.m_access_tab_id}','${maps.isactive}','${maps.created_at}','${maps.updated_at}')`, (err, result) => {
        if (err) throw err;
        res.json({
            'error_code': null,
            'message': 'USER ME',
            'data': result
        });
    });
}
export async function login(req: any, res: any) {
    const auth = await new Auth().attempt(req.body);
    res.json(new Auth().generateToken('Angeline_universe'));
    // connection.query(`select * from m_user_tab where id = ${req.body.id}`, (err, result) => {
    //     if (err) throw err;
    //     res.json();
    // });
}
export async function show(req: any, res: any) {
    connection.query(`select * from m_user_tab where id = ${req.body.id}`, (err, result) => {
        if (err) throw err;
        res.json();
    });
}
export async function update(req: any, res: any) {
    connection.query(`UPDATE m_user_tab SET where id = ${req.body.id}`, (err, result) => {
        if (err) throw err;
        res.json({
            'error_code': null,
            'message': 'USER ME',
            'data': result
        });
    });
}
export async function destroy(req: any, res: any) {
    connection.query(`DELETE m_user_tab SET where id = ${req.body.id}`, (err, result) => {
        if (err) throw err;
        res.json({
            'error_code': null,
            'message': 'USER ME',
            'data': result
        });
    });
}