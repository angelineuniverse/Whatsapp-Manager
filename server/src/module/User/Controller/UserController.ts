import connection from '../../../connection/database.ts';
import { Auth } from '../../../../provider/Auth.ts';
import moment from 'moment';
import { responses, responsesTable } from '../../../utils/callback.ts';
import { MUserTab } from '../Model/MUserTab.ts';
import { ModelForm } from 'model/modelForm.ts';
import { eloquentWithArray } from '../../../utils/eloquent.ts';
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
        created_at: moment().format('YYYY-MM-DD HH:mm:ss'),
        updated_at: moment().format('YYYY-MM-DD HH:mm:ss'),
    };
    connection.query(`
            INSERT INTO m_user_tab
            (m_company_tab_id,token,email,password,contact,m_status_tab_id,m_access_tab_id,created_at,updated_at)
            VALUES
            (?,?,?,?,?,?,?,?,?,?)`,
        [maps.m_company_tab_id,maps.token ?? null, maps.email, maps.password, maps.contact, 1, maps.m_access_tab_id, maps.created_at, maps.updated_at]
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
    const auth = await new Auth().attempt(req.body);
    if (auth?.status) {
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
    } else {
        return res.status(401).json(responses(
            "USER FAILURE LOGIN",
            null,
            401,
            {
                theme: 'error',
                title: "Login Tidak Berhasil",
                body: auth?.error_attempt
            }
        )
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
export async function profileForm(req: any, res: any) {
    const user: MUserTab = await new Auth().user(req) as MUserTab;
    connection.query(`select * from m_access_tab where id > ${user?.m_access_tab_id}`, (err, result) => {
        const data: Array<ModelForm> = [
            { key: 'name', type: 'text', label: 'Nama', isRequired: true, placeholder: 'Masukan Nama Anda', autosize: true },
            { key: 'email', type: 'text', label: 'Email', isRequired: true, placeholder: 'Masukan Email Anda', autosize: true },
            { key: 'contact', type: 'number', label: 'Contact', isRequired: true, placeholder: 'Masukan No Whatsapp Anda', autosize: true },
            {
                key: 'm_access_tab_id', type: 'select',
                label: 'Access Pengguna', isRequired: true,
                placeholder: 'Pilih Access Pengguna',
                autosize: true,
                classNameOption: 'text-black text-sm',
                list: {
                    options: result as Array<any>,
                    keyValue: 'id',
                    keyoption: 'title'
                }
            },
        ];
        return res.json(responses(
            "USER FORM",
            data,
            200
        ))
    })
}
export async function profileIndex(req: any, res: any) {
    const user: MUserTab = await new Auth().user(req) as MUserTab;
    const column: Array<any> = [
        { key: 'company_name', type: 'string', name: 'Company' },
        { key: 'name', type: 'string', name: 'Nama', className: "font-interbold min-w-[200px]" },
        { key: 'email', type: 'string', name: 'Email', className: "min-w-[110px]" },
        { key: 'contact', type: 'string', name: 'Contact' },
        { key: 'access.title', type: 'string', name: 'Access Akun' },
        { key: 'status', type: 'status', name: 'Status Akun' },
        { key: 'action', type: 'action', ability: ['EDIT','DELETE']},
    ];
    try {
        const [users]: Array<any> = await connection.promise().query(
            `select 
                a.*,
                b.name as company_name
            from m_user_tab a
            left join m_company_tab b on a.m_company_tab_id = b.id
            where m_company_tab_id = ?`,
            [user.m_company_tab_id]);
        const [statused]: Array<any> = await connection
            .promise()
            .query(`
            select
            a.*
            from
            m_status_tab a
        `);
        const [accessed]: Array<any> = await connection
            .promise()
            .query(`
            select
            a.*
            from
            m_access_tab a
        `);
        const eloquent = eloquentWithArray(users, statused, 'm_status_tab_id', 'id', 'status');
        const eloquentAcess = eloquentWithArray(eloquent, accessed, 'm_access_tab_id', 'id', 'access');
        return res.json(responsesTable(
            "USER FORM",
            column,
            eloquentAcess,
            200
        ))
    } catch (error) {
        console.log(error);
        
    }
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