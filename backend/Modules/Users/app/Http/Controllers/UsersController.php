<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Company\Models\MProjectTab;
use Modules\Company\Models\MRolesTab;
use Modules\Master\Models\MCodeTab;
use Modules\Users\Emails\MailRegister;
use Modules\Users\Models\MUserTab;
use Modules\Users\Models\TCompanyAdminTab;

class UsersController extends Controller
{
    protected $mUserTab, $controller, $mRolesTab, $tCompanyAdminTab, $mProjectTab;
    public function __construct(
        Controller $controller,
        MUserTab $mUserTab,
        MRolesTab $mRolesTab,
        MProjectTab $mProjectTab,
        TCompanyAdminTab $tCompanyAdminTab
    ) {
        $this->controller = $controller;
        $this->mUserTab = $mUserTab;
        $this->tCompanyAdminTab = $tCompanyAdminTab;
        $this->mRolesTab = $mRolesTab;
        $this->mProjectTab = $mProjectTab;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->successList(
            "LIST PROJECT",
            $this->mUserTab->where('m_company_tabs_id', auth()->user()->m_company_tabs_id)->query($request)->paginate(10),
            array(
                [
                    'name' => 'Pengguna',
                    'type' => 'array',
                    'key' => 'title',
                    'useSort' => true,
                    "classNameRow" => "text-start",
                    'child' => array(
                        [
                            "type" => "string",
                            "key" => "name",
                            "className" => 'font-intersemibold pb-1 mb-1 border-b border-blue-500'
                        ],
                        [
                            "type" => "string",
                            "key" => "email",
                            'className' => 'text-xs'
                        ],
                    )
                ],
                [
                    "name" => "Role",
                    "type" => "string",
                    "className" => "text-center font-intermedium text-xs",
                    "key" => "user_role.role.title",
                ],
                [
                    "name" => "Contact",
                    "type" => "string",
                    "classNameRow" => "text-start text-xs",
                    "key" => "contact",
                ],
                [
                    "name" => "Code Pengguna",
                    "type" => "string",
                    "className" => "text-center font-intermedium text-xs",
                    "key" => "code",
                ],
                [
                    "name" => "Status",
                    "type" => "status",
                    "key" => "status",
                    "className" => 'text-xs'
                ],
                [
                    "name" => "Action",
                    "type" => "action_status",
                    "ability" => array(
                        [
                            'key' => 'show',
                            'show_by' => 'm_status_tabs_id',
                            'title' => 'Detail',
                            'theme' => 'success',
                            'show_value' => [1, 2]
                        ],
                        [
                            'key' => 'delete',
                            'show_by' => 'm_status_tabs_id',
                            'title' => 'Hapus',
                            'theme' => 'error',
                            'show_value' => [1, 2]
                        ]
                    ),
                ],
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->controller->resSuccess(
            array(
                [
                    "type" => "text",
                    "label" => "Nama Pengguna",
                    "key" => 'name',
                    'isRequired' => true,
                    'name' =>  null,
                    'placeholder' => 'Masukan nama pengguna'
                ],
                [
                    "type" => "text",
                    "label" => "Email",
                    "key" => 'email',
                    'isRequired' => true,
                    'email' =>  null,
                    'placeholder' => 'Masukan alamat email aktif'
                ],
                [
                    "type" => "number",
                    "label" => "No. Whatsapp",
                    "key" => 'contact',
                    'isRequired' => true,
                    'contact' =>  null,
                    'placeholder' => 'Masukan Nomor Whatsapp'
                ],
                [
                    "type" => "upload",
                    "label" => "Foto Profile",
                    "key" => 'avatar',
                    'isRequired' => true,
                    'avatar' =>  null,
                    'accept' => 'image/png,image/jpeg,image/jpg',
                    'description' => "Supported PNG/JPEG/JPG ( Max 5Mb )"
                ],
                [
                    "key" => 'm_project_tabs_id',
                    "label" => "Pilih Project",
                    "type" => "select",
                    "isRequired" => true,
                    'useClear' => true,
                    "m_project_tabs_id" => null,
                    "placeholder" => "Pilih Project",
                    "description" => 'Hanya Project yang statusnya Active yang dapat dipilih',
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mProjectTab->where('m_status_tabs_id', 1)->get()
                    ]
                ],
                [
                    "key" => 'm_roles_tabs_id',
                    "label" => "Pilih Role",
                    "type" => "select",
                    "isRequired" => true,
                    'useClear' => true,
                    "m_roles_tabs_id" => null,
                    "readonly" => true,
                    "placeholder" => "Pilih Role",
                    "description" => 'Hanya Role yang statusnya Active yang dapat dipilih',
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mRolesTab->where('m_project_tabs_id', 1)->get()
                    ]
                ],
            )
        );
    }

    public function login(Request $request)
    {
        $this->controller->validing($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8',
        ]);

        if (!Auth::attempt($request->all())) abort(400, "Your information not valid");
        if (!$user = $this->mUserTab->where('email', $request->email)->where('m_status_tabs_id', 1)->first()) abort(501, "Your account not found !");
        $token = $user->createToken('ANGELINEUNIVERSE')->plainTextToken;
        return $this->controller->resSuccess([
            'token' => $token
            ],
            [
                'title' => 'Masuk kembali berhasil',
                'body' => 'Selamat datang kembali di platform Angeline Universe',
                'theme' => 'success'
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validing($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:m_user_tabs,email',
            'password' => 'required|min:8',
            'm_company_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = MCodeTab::generateCode('USR');
            $request['m_status_tabs_id'] = 2;
            $user = $this->mUserTab->create($request->all());
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR_' . $request->code . '_' . $file->getClientOriginalName();
                $file->move(public_path('avatar'), $filename);
                $user->update(['avatar' => $filename]);
            }
            $token = $user->createToken('ANGELINEUNIVERSE');
            Mail::to($request->email)->send(new MailRegister($token->plainTextToken));
            DB::commit();
            return $this->controller->resSuccess("CREATED", [
                'title' => 'User berhasil dibuat',
                'body' => 'Harap periksa email pengguna dan lakukan aktivasi akun agar dapat menggunakan aplikasi',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('users::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('users::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validing($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        try {
            DB::beginTransaction();
            $user = $this->mUserTab->where('id', $id)->first();
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR_' . $user->code . '_' . $file->getClientOriginalName();
                $this->controller->unlink_filex(public_path('avatar'), $user->avatar);
                $file->move(public_path('avatar'), $filename);
                $user->update(['avatar' => $filename]);
            }
            $user->update($request->all());
            DB::commit();
            return $this->controller->resSuccess("CREATED", [
                'title' => 'Informasi User berhasil diubah',
                'body' => 'Informasi User berhasil diubah dan disimpan ke dalam system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->mUserTab->where('id', $id)->delete();
            DB::commit();
            return $this->controller->resSuccess("DELETED", [
                'title' => 'User berhasil dihapus',
                'body' => 'Semua informasi terkait user tersebut berhasil dihapus',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }

    public function activated($token)
    {
        $tokens = PersonalAccessToken::findToken($token);
        $user = $tokens->tokenable;
        if ($user) {
            /** Map untuk Super Admin */
            $admin = $this->tCompanyAdminTab->where('m_company_tabs_id', $user->m_company_tabs_id)->first();
            if (!isset($admin)) {
                $this->tCompanyAdminTab->create([
                    'm_user_tabs_id' => $user->id,
                    'm_company_tabs_id' => $user->m_company_tabs_id,
                ]);
            }
            DB::beginTransaction();
            $user->update(['m_status_tabs_id' => 1]);
            DB::commit();
            return view('users::email.activated');
        }
        abort(404);
    }
}
