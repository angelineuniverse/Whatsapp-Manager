<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Master\Models\MCodeTab;
use Modules\Users\Emails\MailRegister;
use Modules\Users\Models\MUserTab;

class UsersController extends Controller
{
    protected $mUserTab;
    protected $controller;
    public function __construct(
        Controller $controller,
        MUserTab $mUserTab
    ) {
        $this->controller = $controller;
        $this->mUserTab = $mUserTab;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users::create');
    }

    public function login(Request $request)
    {
        $this->controller->validing($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8',
        ]);

        if (!Auth::attempt($request->all())) abort(400, "Your information not valid");
        if (!$user = $this->mUserTab->where('email', $request->email)->where('m_status_tabs_id', 2)->first()) abort(501, "Your account not found !");
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
            $request['m_status_tabs_id'] = 3;
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
            DB::beginTransaction();
            $user->update([
                'm_status_tabs_id' => 2,
            ]);
            DB::commit();
            return view('users::email.activated');
        }
        abort(404);
    }
}
