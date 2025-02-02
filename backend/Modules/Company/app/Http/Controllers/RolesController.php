<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MProjectTab;
use Modules\Company\Models\MRolesMenuTab;
use Modules\Company\Models\MRolesTab;
use Modules\Master\Models\MActionTab;
use Modules\Master\Models\MMenuTab;
use Modules\Master\Models\MModuleTab;
use Modules\Users\Models\TUserLogTab;
use Modules\Users\Models\TUserRolesTab;

class RolesController extends Controller
{
    protected $controller,
        $tUserLogTab,
        $mRolesTab,
        $mMenuTab,
        $mRolesMenuTab,
        $mActionTab,
        $tUserRolesTab,
        $mProjectTab;
    public function __construct(
        Controller $controller,
        MRolesTab $mRolesTab,
        MProjectTab $mProjectTab,
        MMenuTab $mMenuTab,
        MRolesMenuTab $mRolesMenuTab,
        TUserLogTab $tUserLogTab,
        MActionTab $mActionTab,
        TUserRolesTab $tUserRolesTab
    ) {
        $this->controller = $controller;
        $this->tUserRolesTab = $tUserRolesTab;
        $this->mRolesTab = $mRolesTab;
        $this->mProjectTab = $mProjectTab;
        $this->mRolesMenuTab = $mRolesMenuTab;
        $this->mMenuTab = $mMenuTab;
        $this->tUserLogTab = $tUserLogTab;
        $this->mActionTab = $mActionTab;
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
                ["type" => 'action', "ability" => $this->getAccessAction()]
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menu = $this->mMenuTab->whereNotIn('id', [1, 7])->get();
        $action = $this->mActionTab->all();
        foreach ($menu as $key => $value) {
            $value['action'] = $action;
        }
        return $this->controller->resSuccess([
            'form' => array(
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
            ),
            'menu' => $menu
        ]);
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
            $role = $this->mRolesTab->create($request->all());
            if (isset($request->access)) {
                foreach ($request->access as $key => $value) {
                    if (count($value['action']) > 0) {
                        $this->mRolesMenuTab->create([
                            'm_roles_tabs_id' => $role->id,
                            'm_menu_tabs_id' => $value['menu'],
                            'menu_parent_id' => $value['menu_parent_id'],
                            'm_action_tabs_id' => count($value['action']) > 0 ? implode(',', $value['action']) : null
                        ]);
                    }
                }
            }
            $this->tUserLogTab->create([
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$ROLES,
                'm_action_tabs_id' => MActionTab::$ADD,
                'description' => "Tambah Roles Baru",
            ]);
            DB::commit();
            return $this->controller->resSuccess($role, [
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
    public function show($id)
    {
        $id = $this->mMenuTab->where('title', 'like', '%Roles%')->pluck('id');
        $tUserRolesTab = $this->tUserRolesTab
            ->where('m_user_tabs_id', auth()->user()->id)
            ->with('role', function ($a) use ($id) {
                $a->with('role_menu', function ($b) use ($id) {
                    $b->where('m_menu_tabs_id', $id);
                });
            })
            ->first();
        if (!$tUserRolesTab) return $this->controller->resSuccess(true); // this Owner
        $access = array();
        foreach ($tUserRolesTab->role->role_menu as $i => $value) {
            $actionTabs = explode(',', $value->m_action_tabs_id);
            foreach ($actionTabs as $j => $item) {
                $action = $this->mActionTab->where('id', $item)->first();
                array_push($access, $action);
            }
        }
        return $this->controller->resSuccess($access);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->mRolesTab->where('id', $id)->detail()->first();
        $masterMenu = $this->mMenuTab->whereNotIn('id', [1, 7])->get();
        $selectedMenu = $this->mRolesMenuTab->where('m_roles_tabs_id', $id)->get();

        foreach ($masterMenu as $master) {
            $action = $this->mActionTab->all();
            foreach ($selectedMenu as $key => $value) {
                if ($master->id == $value->m_menu_tabs_id) {
                    $master->selected = true;
                    $actionTabs = explode(',', $value->m_action_tabs_id);
                    foreach ($actionTabs as $j => $actab) {
                        foreach ($action as $i => $act) {
                            if ($act->id == $actab) {
                                $act->selected = true;
                            }
                        }
                    }
                }
                $master['action'] = $action;
            }
        }
        return $this->controller->resSuccess([
            "form" =>
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
            "menu" => $masterMenu,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validing($request->all(), [
            'title' => 'required',
            'color' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $this->mRolesTab->where('id', $id)->update($request->except(['access']));
            if (isset($request->access)) {
                $this->mRolesMenuTab->where('m_roles_tabs_id', $id)->delete();
                foreach ($request->access as $key => $value) {
                    if (count($value['action']) > 0) {
                        $this->mRolesMenuTab->create([
                            'm_roles_tabs_id' => $id,
                            'm_menu_tabs_id' => $value['menu'],
                            'menu_parent_id' => $value['menu_parent_id'],
                            'm_action_tabs_id' => count($value['action']) > 0 ? implode(',', $value['action']) : null
                        ]);
                    }
                }
            }
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

    public function getAccessAction()
    {
        $id = $this->mMenuTab->where('title', 'like', '%Roles%')->pluck('id');
        $tUserRolesTab = $this->tUserRolesTab
            ->where('m_user_tabs_id', auth()->user()->id)
            ->with('role', function ($a) use ($id) {
                $a->with('role_menu', function ($b) use ($id) {
                    $b->where('m_menu_tabs_id', $id);
                });
            })
            ->first();
        if (!$tUserRolesTab) return array("EDIT", "DELETE");  // this Owner
        $access = array();
        foreach ($tUserRolesTab->role->role_menu as $i => $value) {
            $actionTabs = explode(',', $value->m_action_tabs_id);
            foreach ($actionTabs as $j => $item) {
                $action = $this->mActionTab->where('id', $item)->first();
                switch ($action->action) {
                    case 'SHOW':
                        array_push($access, "EDIT");
                        break;
                    case 'DELETE':
                        array_push($access, "DELETE");
                        break;
                    default:
                        break;
                }
            }
        }
        return $access;
    }
}
