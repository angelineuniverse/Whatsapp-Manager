<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\Models\MUserTab;
use Modules\Users\Models\TUserLogTab;

class ProfileController extends Controller
{
    protected
        $controller,
        $tUserLogTab,
        $mUserTab;

    public function __construct(
        Controller $controller,
        MUserTab $mUserTab,
        TUserLogTab $tUserLogTab
    ) {
        $this->controller = $controller;
        $this->mUserTab = $mUserTab;
        $this->tUserLogTab = $tUserLogTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->resSuccess([
            'detail' => auth()->user(),
            'form' => array(
                [
                    "type" => "text",
                    "label" => "Nama Pengguna",
                    "key" => 'name',
                    'isRequired' => true,
                    'name' =>  auth()->user()->name,
                    'placeholder' => 'Masukan nama pengguna'
                ],
                [
                    "type" => "text",
                    "label" => "Email",
                    "key" => 'email',
                    'isRequired' => true,
                    'email' =>  auth()->user()->email,
                    'placeholder' => 'Masukan alamat email aktif',
                    'description' => "Masukan Email aktif untuk mendapat Email Aktivasi"
                ],
                [
                    "type" => "number",
                    "label" => "No. Whatsapp",
                    "key" => 'contact',
                    'isRequired' => true,
                    'contact' =>  auth()->user()->contact,
                    'placeholder' => 'Masukan Nomor WhatsApp',
                    'description' => "Masukan No. WhatsApp aktif untuk mendapat Notifikasi"
                ],
                [
                    "type" => "password",
                    "label" => "Password Baru",
                    "key" => 'password',
                    'isRequired' => true,
                    'password' => null,
                ],
                [
                    "type" => "upload",
                    "label" => "Foto Profile",
                    "key" => 'avatar',
                    'isRequired' => true,
                    'avatar' =>  auth()->user()->avatar,
                    'filename' =>  auth()->user()->avatar,
                    'accept' => 'image/png,image/jpeg,image/jpg',
                    'description' => "Supported PNG/JPEG/JPG ( Max 5Mb )"
                ],

            )
        ]);
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return $this->controller->successList(
            'LOG USER',
            $this->tUserLogTab->where('m_user_tabs_id', auth()->user()->id)->detail()->orderBy('id', 'desc')->paginate(5),
            array(
                [
                    'key' => 'description',
                    'name' => 'Detail',
                    'type' => 'string',
                    'classNameRow' => 'text-start',
                    'className' => 'text-xs'
                ],
                [
                    'key' => 'module.module',
                    'name' => 'Module',
                    'type' => 'string',
                    'className' => 'text-center font-intersemibold'
                ],
                [
                    'key' => 'action',
                    'name' => 'Action',
                    'type' => 'custom',
                ],
                [
                    'key' => 'created_at',
                    'name' => 'Waktu',
                    'type' => 'datetime',
                    'classNameRow' => 'text-start',
                    'className' => 'text-xs font-interregular'

                ],
            )
        );
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
