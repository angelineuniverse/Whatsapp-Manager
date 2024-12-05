<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MProjectTab;
use Modules\Company\Models\MUnitStatusTabs;
use Modules\Master\Models\MActionTab;
use Modules\Master\Models\MModuleTab;
use Modules\Users\Models\TUserLogTab;

class UnitStatusController extends Controller
{
    protected $controller,
        $tUserLogTab,
        $mUnitStatusTabs,
        $mProjectTab;
    public function __construct(
        Controller $controller,
        MUnitStatusTabs $mUnitStatusTabs,
        MProjectTab $mProjectTab,
        TUserLogTab $tUserLogTab
    ) {
        $this->controller = $controller;
        $this->mUnitStatusTabs = $mUnitStatusTabs;
        $this->mProjectTab = $mProjectTab;
        $this->tUserLogTab = $tUserLogTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->successList(
            'INDEX UNIT STATUS',
            $this->mUnitStatusTabs->search($request)->detail()->orderBy($request->key ?? 'id', $request->type ?? 'desc')->paginate(10),
            [
                ["name" => "Nama Status", 'useSort' => true, "key" => "title", 'type' => 'string', 'classNameRow' => 'text-start font-intersemibold'],
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
                    "label" => "Status Unit",
                    "type" => "text",
                    "title" => null,
                    "isRequired" => true,
                    "placeholder" => "Masukan status unit",
                ],
                [
                    "key" => 'color',
                    "label" => "Warna Status",
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
            'title' => 'required|unique:m_unit_status_tabs,title',
            'color' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['m_company_tabs_id'] = auth()->user()->m_company_tabs_id;
            $access = $this->mUnitStatusTabs->create($request->all());
            $this->tUserLogTab->create([
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$ROLES,
                'm_action_tabs_id' => MActionTab::$ADD,
                'description' => "Tambah Status Unit Baru",
            ]);
            DB::commit();
            return $this->controller->resSuccess($access, [
                "title" => "Status berhasil dibuat !",
                "body" => "Status baru berhasil disimpan dan ditambahkan kedalam list"
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
        return $this->controller->resSuccess($this->mUnitStatusTabs->where('m_project_tabs_id', $id)->search($request)->get());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->mUnitStatusTabs->where('id', $id)->detail()->first();
        return $this->controller->resSuccess(
            array(
                [
                    "key" => 'title',
                    "label" => "Status Unit",
                    "type" => "text",
                    "title" => $detail->title,
                    "isRequired" => true,
                    "placeholder" => "Masukan status unit",
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
            'title' => 'required|unique:m_unit_status_tabs,title',
            'color' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $this->mUnitStatusTabs->where('id', $id)->update($request->all());
            $this->tUserLogTab->create([
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$UNIT,
                'm_action_tabs_id' => MActionTab::$UPDATE,
                'description' => "Update Status Unit",
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
            ]);
            DB::commit();
            return $this->controller->resSuccess("UPDATED", [
                "title" => "Status Unit berhasil diupdate !",
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
            $this->mUnitStatusTabs->where('id', $id)->delete();
            $this->tUserLogTab->create([
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$UNIT,
                'm_action_tabs_id' => MActionTab::$DELETE,
                'description' => "Hapus Status Unit",
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
            ]);
            DB::commit();
            return $this->controller->resSuccess(null, [
                "title" => "Status Unit berhasil dihapus !",
                "body" => "Semua informasi dan koneksi terhadap Status Unit tersebut berhasil dihapus"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }
}
