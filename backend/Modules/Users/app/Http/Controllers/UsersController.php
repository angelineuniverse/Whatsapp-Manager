<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Models\MRolesMenuTab;
use Modules\Master\Models\MActionTab;
use Modules\Master\Models\MCodeTab;
use Modules\Master\Models\MMenuTab;
use Modules\Master\Models\MModuleTab;
use Modules\Users\Emails\MailRegister;
use Modules\Users\Models\MUserTab;
use Modules\Users\Models\TCompanyAdminTab;
use Modules\Users\Models\TUserLogTab;
use Modules\Users\Models\TUserProjectTab;
use Modules\Users\Models\TUserRolesTab;

class UsersController extends Controller
{
    protected
        $mUserTab,
        $controller,
        $mRolesMenuTab,
        $tCompanyAdminTab,
        $mMenuTab,
        $tUserRolesTab,
        $tUserLogTab,
        $mActionTab,
        $tUserProjectTab;
    public function __construct(
        Controller $controller,
        MUserTab $mUserTab,
        MRolesMenuTab $mRolesMenuTab,
        MMenuTab $mMenuTab,
        TUserRolesTab $tUserRolesTab,
        MActionTab $mActionTab,
        TUserProjectTab $tUserProjectTab,
        TUserLogTab $tUserLogTab,
        TCompanyAdminTab $tCompanyAdminTab
    ) {
        $this->controller = $controller;
        $this->mActionTab = $mActionTab;
        $this->tUserRolesTab = $tUserRolesTab;
        $this->tUserProjectTab = $tUserProjectTab;
        $this->tUserLogTab = $tUserLogTab;
        $this->mUserTab = $mUserTab;
        $this->tCompanyAdminTab = $tCompanyAdminTab;
        $this->mRolesMenuTab = $mRolesMenuTab;
        $this->mMenuTab = $mMenuTab;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $isSuper = $this->tCompanyAdminTab
            ->where('m_user_tabs_id', auth()->user()->id)
            ->where('m_company_tabs_id', auth()->user()->m_company_tabs_id)
            ->first();
        $menu = array();
        if ($isSuper) $menu = $this->mMenuTab->where('m_status_tabs_id', 1)->detail()->get();
        else {
            $userRoles = $this->tUserRolesTab->with(['user', 'role' => function ($a) {
                $a->with('role_menu', function ($b) {
                    $b->with('menu');
                });
            }])->whereHas('user', function ($b) {
                $b->where('id', auth()->user()->id);
            })->first();
            // return $this->controller->resSuccess($userRoles);
            foreach ($userRoles->role->role_menu as $key => $value) {
                if ($value->menu) array_push($menu, $value->menu);
            }
        }
        return $this->controller->resSuccess($menu);
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
                    'placeholder' => 'Masukan alamat email aktif',
                    'description' => "Masukan Email aktif untuk mendapat Email Aktivasi"
                ],
                [
                    "type" => "number",
                    "label" => "No. Whatsapp",
                    "key" => 'contact',
                    'isRequired' => true,
                    'contact' =>  null,
                    'placeholder' => 'Masukan Nomor WhatsApp',
                    'description' => "Masukan No. WhatsApp aktif untuk mendapat Notifikasi"
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
        if (!$user = $this->mUserTab->where('email', $request->email)->first()) abort(501, "Your account not found !");
        if (!$user = $this->mUserTab->where('email', $request->email)->where('m_status_tabs_id', 1)->first()) abort(501, "Your account not active !");
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
            'contact' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = MCodeTab::generateCode('USR');
            $request['m_status_tabs_id'] = 2;
            $request['m_company_tabs_id'] = auth()->user()->m_company_tabs_id;
            $user = $this->mUserTab->create($request->all());
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR_' . $request->code . '_' . $file->getClientOriginalName();
                $file->move(public_path('avatar'), $filename);
                $user->update(['avatar' => $filename]);
            }
            $this->tUserRolesTab->create([
                'm_roles_tabs_id' => $request->m_roles_tabs_id,
                'm_user_tabs_id' => $user->id,
            ]);
            $this->tUserProjectTab->create([
                'm_project_tabs_id' => $request->m_project_tabs_id,
                'm_user_tabs_id' => $user->id,
            ]);
            $token = $user->createToken('ANGELINEUNIVERSE');
            Mail::to($request->email)->send(new MailRegister($token->plainTextToken));
            $this->tUserLogTab->create(['m_company_tabs_id' => auth()->user()->m_company_tabs_id,
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$PENGGUNA,
                'm_action_tabs_id' => MActionTab::$ADD,
                'description' => "Tambah Pengguna Baru",
            ]);
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
    public function edit($id, Request $request)
    {
        $user = $this->mUserTab->where('id', $id)->query($request)->first();
        return $this->controller->resSuccess(
            array(
                [
                    "type" => "text",
                    "label" => "Nama Pengguna",
                    "key" => 'name',
                    'isRequired' => true,
                    'name' =>  $user->name,
                    'placeholder' => 'Masukan nama pengguna'
                ],
                [
                    "type" => "text",
                    "label" => "Email",
                    "key" => 'email',
                    'isRequired' => true,
                    'email' =>  $user->email,
                    'placeholder' => 'Masukan alamat email aktif',
                    'description' => "Masukan Email aktif untuk mendapat Email Aktivasi"
                ],
                [
                    "type" => "number",
                    "label" => "No. Whatsapp",
                    "key" => 'contact',
                    'isRequired' => true,
                    'contact' =>  $user->contact,
                    'placeholder' => 'Masukan Nomor WhatsApp',
                    'description' => "Masukan No. WhatsApp aktif untuk mendapat Notifikasi"
                ],
                [
                    "type" => "upload",
                    "label" => "Foto Profile",
                    "key" => 'avatar',
                    'isRequired' => true,
                    'avatar' =>  $user->avatar,
                    'filename' => $user->avatar,
                    'accept' => 'image/png,image/jpeg,image/jpg',
                    'description' => "Supported PNG/JPEG/JPG ( Max 5Mb )"
                ],
                [
                    "key" => 'm_project_tabs_id',
                    "label" => "Pilih Project",
                    "type" => "select",
                    "isRequired" => true,
                    'useClear' => true,
                    "m_project_tabs_id" => $user->project->m_project_tabs_id,
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
                    "m_roles_tabs_id" => $user->user_role->m_roles_tabs_id,
                    "placeholder" => "Pilih Role",
                    "description" => 'Hanya Role yang statusnya Active yang dapat dipilih',
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mRolesTab->where('m_project_tabs_id', $user->project->m_project_tabs_id)->get()
                    ]
                ],
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validing($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required|min:11',
        ]);

        try {
            DB::beginTransaction();
            $user = $this->mUserTab->where('id', $id)->first();
            $user->update($request->all());
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR_' . $user->code . '_' . $file->getClientOriginalName();
                $this->controller->unlink_filex(public_path('avatar'), $user->avatar);
                $file->move(public_path('avatar'), $filename);
                $user->update(['avatar' => $filename]);
            }
            $this->tUserLogTab->create([
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$PENGGUNA,
                'm_action_tabs_id' => MActionTab::$UPDATE,
                'description' => "Update Informasi Pengguna",
            ]);
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
        auth()->user()->currentAccessToken()->delete();
        return $this->controller->resSuccess(null, [
            'title' => 'Kamu telah keluar system',
            'body' => 'Semua informasi terkait user tersebut berhasil dihapus',
            'theme' => 'success'
        ]);
    }
}
