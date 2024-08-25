import { generateCode } from '../../Master/Model/MCodeTab.ts';
import connection from '../../../connection/database.ts';
import { responses } from '../../../utils/callback.ts';
import moment from 'moment';
export async function index(req: any, res: any) {
    connection.query('select * from m_company_tab where m_status_tab_id = 2', (err, result) => {
        if (err) throw err;
        res.json(responses("ALL COMPANY", result));
    });
}

export async function store(req: any, res: any) {
    const code = await generateCode('CMP');
    const maps = {
        code: code,
        name: req.body.name,
        email: req.body.email,
        contact: req.body.contact,
        m_status_tab_id: req.body.m_status_tab_id,
        created_at: moment().format('YYYY-MM-DD HH:mm:ss'),
        updated_at: moment().format('YYYY-MM-DD HH:mm:ss'),
    };
    connection.query(`INSERT INTO m_company_tab (code,name,email,contact,m_status_tab_id,created_at,updated_at) VALUES ('${maps.code}','${maps.name}','${maps.email}','${maps.contact}',${maps.m_status_tab_id},'${maps.created_at}','${maps.updated_at}')`, (err, result) => {
        if (err) throw err;
        res.json(responses("STORE COMPANY", maps));
    });
}

export async function show(req: any, res: any) {
    connection.query(`select * from m_company_tab where id = ${req.params.id}`, (err, result) => {
        if (err) throw err;
        res.json(responses("ME COMPANY", result));
    });
}

export async function update(req: any, res: any) {
    const maps = {
        name: req.body.name,
        email: req.body.email,
        contact: req.body.contact,
        m_status_tab_id: req.body.m_status_tab_id,
        updated_at: moment().format('YYYY-MM-DD HH:mm:ss'),
    };
    connection.query(`UPDATE m_company_tab SET name='${maps.name}',email='${maps.email}',contact='${maps.contact}',m_status_tab_id='${maps.m_status_tab_id}',updated_at='${maps.updated_at}' where id = ${req.params.id}`, (err, result) => {
        if (err) throw err;
        res.json(responses("UPDATE COMPANY", maps));
    });
}

export async function destroy(req: any, res: any) {
    connection.query(`DELETE FROM m_company_tab where id = ${req.params.id}`, (err, result) => {
        if (err) throw err;
        res.json(responses("DELETE COMPANY", result));
    });
}