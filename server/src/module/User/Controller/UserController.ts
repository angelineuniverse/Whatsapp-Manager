import connection from '../../../connection/database.ts';
import { Auth } from '../../../../provider/Auth.ts';
import moment from 'moment';
import { responses } from '../../../utils/callback.ts';
import { MUserTab } from '../Model/MUserTab.ts';
import { ModelForm } from 'model/modelForm.ts';
export async function index(req: any, res: any) {
    connection.query('select * from m_user_tab where isactive = 1 and m_company_tab_id = ?',[req.query.company_id], (err, result) => {
        if (err) throw err;
        res.json(result as Array<MUserTab>);
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
            return res.status(200).json(
                responses("USER CREATED", null, 200,
                    {
                        theme: 'success',
                        title: "Akun baru berhasil dibuat",
                        body: "Selamat akun anda berhasil dibuat, Periksa email untuk aktivasi akun"
                    }
                ))
    });
}
export async function login(req: any, res: any) {
    const auth: boolean = await new Auth().attempt(req.body);
    if (auth) {
        connection.query(
            `SELECT * FROM m_user_tab a WHERE a.email=?`, [req.body.email], async (err, result: any) => {
                if (err) throw err;
                if (result.length < 1) {
                    return res.status(200).json(responses(
                        "USER NOT FOUND",
                        null,
                        200,
                        {
                            theme: 'error',
                            title: "Akun tidak ditemukan",
                            body: "Mohon maaf, akun anda belum terdaftar di sistem kami"
                        }
                    ))
                }
                const response = result[0] as MUserTab; 
                const token = await new Auth().generateToken(response.id, process.env.TOKEN_KEY);
                return res.status(200).json(responses(
                        "USER SUCCESS LOGIN",
                        {
                            token: token
                        },
                        200,
                        {
                            theme: 'success',
                            title: "Selamat datang kembali",
                            body: "Selamat datang kembali ke WhatsApp Manager"
                        }
                    )
                )
            }
        )
    }
}
export async function show(req: any, res: any) {
    const user = await new Auth().user(req);
    if (!user) {
        return res.status(401).json(responses(
            "UNAUTHORIZED",
            null,
            401,
            {
                theme: 'error',
                title: "Token Expired, Anda harus login terlebih dahulu",
                body: "Mohon maaf, anda harus login terlebih dahulu untuk mengakses halaman ini"
            }
        ))
    }
    return res.json(responses(
            "USER DETAIL",
            user,
            200
        ));
}
export async function formProfile(req: any, res: any) {
    const user: Array<ModelForm> = [
        { key: 'email', type: 'text', label: 'Email', isRequired: true, placeholder: 'Masukan Email Anda', autosize: true }
    ];
    return res.json(responses(
        "USER FORM",
        user,
        200
    ))
}
// INI NANTI LIAT KONDISI
export async function update(req: any, res: any) {
    connection.query(`UPDATE m_user_tab SET where id = ${req.body.id}`, (err, result) => {
        if (err) throw err;
        res.status(200).json(responses(
            "USER UPDATED",
            result,
            200,
            {
                theme: 'error',
                title: "Data berhasil di update",
                body: "Detail informasi anda sekarang telah terupdate"
            }
        ))
    });
}
export async function destroy(req: any, res: any) {
    connection.query(`DELETE FROM m_user_tab where id = ${req.params.id}`, (err, result) => {
        if (err) throw err;
        res.status(200).json(responses(
            "USER DELETED",
            result,
            200,
            {
                theme: 'error',
                title: "Data berhasil di hapus",
                body: "Data anda telah di bersihkan sepenuhnya dari system"
            }
        ))
    });
}