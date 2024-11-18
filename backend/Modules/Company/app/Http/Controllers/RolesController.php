<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MProjectTab;
use Modules\Company\Models\MRolesTab;
use Modules\Master\Models\MActionTab;
use Modules\Master\Models\MModuleTab;
use Modules\Users\Models\TUserLogTab;

class RolesController extends Controller
{
    protected $controller,
        $tUserLogTab,
        $mRolesTab,
        $mProjectTab;
    public function __construct(
        Controller $controller,
        MRolesTab $mRolesTab,
        MProjectTab $mProjectTab,
        TUserLogTab $tUserLogTab
    ) {
        $this->controller = $controller;
        $this->mRolesTab = $mRolesTab;
        $this->mProjectTab = $mProjectTab;
        $this->tUserLogTab = $tUserLogTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->successList(
            'INDEX ROLES',
            $this->mRolesTab->search($request)->detail()->orderBy($request->key ?? 'id', $request->type ?? 'desc')->paginate(10),
            [
                ["name" => "Nama Roles", 'useSort' => true, "key" => "title", 'type' => 'string', 'classNameRow' => 'text-start font-intersemibold'],
                ["name" => "Project", "key" => "project.title", 'type' => 'string', 'classNameRow' => 'text-start font-intersemibold'],
                ["name" => "Parent Roles", "key" => "parent", 'type' => 'custom', 'className' => 'text-center',],
                ["name" => "Total Pengguna", "key" => "users_count", 'type' => 'string', 'className' => 'text-center', 'classNameRow' => 'text-center'],
                ["name" => "Color", "key" => "color", 'type' => 'custom', 'classNameRow' => 'text-center'],
                ["type" => 'action', "ability" => ["EDIT", "DELETE"]]
            ]
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
                    "key" => 'title',
                    "label" => "Nama Roles",
                    "type" => "text",
                    "title" => null,
                    "isRequired" => true,
                    "placeholder" => "Masukan nama Roles",
                ],
                [
                    "key" => 'parent_id',
                    "label" => "Parent Roles",
                    "type" => "select",
                    "isRequired" => false,
                    "parent_id" => null,
                    "description" => 'Kosongkan apabila tidak memiliki parent Roles',
                    "placeholder" => "Pilih Parent Roles",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mRolesTab->get()
                    ]
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
                    "key" => 'color',
                    "label" => "Warna Roles",
                    "type" => "select",
                    "isRequired" => true,
                    "color" => null,
                    "placeholder" => "Pilih Warna",
                    'list' => [
                        'keyValue' => 'title',
                        'keyOption' => 'title',
                        'options' => array(
                            ['title' => 'green'],
                            ['title' => 'red'],
                            ['title' => 'blue'],
                            ['title' => 'yellow'],
                            ['title' => 'purple'],
                            ['title' => 'orange'],
                            ['title' => 'black'],
                            ['title' => 'gray'],
                        )
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
        $this->controller->validing($request->all(), [
            'title' => 'required|unique:m_roles_tabs,title',
            'color' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $access = $this->mRolesTab->create($request->all());
            $this->tUserLogTab->create([
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$ROLES,
                'm_action_tabs_id' => MActionTab::$ADD,
                'description' => "Tambah Roles Baru",
            ]);
            DB::commit();
            return $this->controller->resSuccess($access, [
                "title" => "Roles berhasil dibuat !",
                "body" => "Roles baru berhasil disimpan dan ditambahkan kedalam list"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id, Request $request)
    {
        return $this->controller->resSuccess($this->mRolesTab->where('m_project_tabs_id', $id)->search($request)->get());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->mRolesTab->where('id', $id)->detail()->first();
        return $this->controller->resSuccess(
            array(
                [
                    "key" => 'title',
                    "label" => "Nama Roles",
                    "type" => "text",
                    "title" => $detail->title,
                    "isRequired" => true,
                    "placeholder" => "Masukan nama Roles",
                ],
                [
                    "key" => 'parent_id',
                    "label" => "Parent Roles",
                    "type" => "select",
                    "isRequired" => false,
                    'useClear' => true,
                    "parent_id" => $detail->parent_id ?? null,
                    "description" => 'Kosongkan apabila tidak memiliki Parent Roles',
                    "placeholder" => "Pilih Parent Roles",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mRolesTab->get()
                    ]
                ],
                [
                    "key" => 'm_project_tabs_id',
                    "label" => "Pilih Project",
                    "type" => "select",
                    "isRequired" => true,
                    "m_project_tabs_id" => $detail->m_project_tabs_id,
                    "placeholder" => "Pilih Project",
                    "description" => 'Hanya Project yang statusnya Active yang dapat dipilih',
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mProjectTab->where('m_status_tabs_id', 1)->get()
                    ]
                ],
                [
                    "key" => 'color',
                    "label" => "Warna Roles",
                    "type" => "select",
                    "isRequired" => true,
                    "color" => $detail->color,
                    "placeholder" => "Pilih warna roles",
                    'list' => [
                        'keyValue' => 'title',
                        'keyOption' => 'title',
                        'options' => array(
                            ['title' => 'green'],
                            ['title' => 'red'],
                            ['title' => 'blue'],
                            ['title' => 'yellow'],
                            ['title' => 'purple'],
                            ['title' => 'orange'],
                            ['title' => 'black'],
                            ['title' => 'gray'],
                        )
                    ]
                ],
            ),
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validing($request->all(), [
            'title' => 'required|unique:m_roles_tabs,title',
            'color' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $this->mRolesTab->where('id', $id)->update($request->all());
            $this->tUserLogTab->create([
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$ROLES,
                'm_action_tabs_id' => MActionTab::$UPDATE,
                'description' => "Update Informasi Roles",
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
            ]);
            DB::commit();
            return $this->controller->resSuccess("UPDATED", [
                "title" => "Roles berhasil diupdate !",
                "body" => "Informasi berhasil diubah dan disimpan kedalam list"
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
            $this->mRolesTab->where('id', $id)->delete();
            $this->tUserLogTab->create([
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$ROLES,
                'm_action_tabs_id' => MActionTab::$DELETE,
                'description' => "Hapus Informasi Roles",
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
            ]);
            DB::commit();
            return $this->controller->resSuccess(null, [
                "title" => "Roles berhasil dihapus !",
                "body" => "Semua informasi dan koneksi terhadap Roles tersebut berhasil dihapus"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }
}
