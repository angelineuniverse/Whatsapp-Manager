<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MProjectTab;
use Modules\Company\Models\MUnitStatusTabs;
use Modules\Company\Models\MUnitTabs;
use Modules\Company\Models\MUnitTypeTabs;
use Modules\Master\Models\MActionTab;
use Modules\Master\Models\MModuleTab;
use Modules\Users\Models\TUserLogTab;

class UnitController extends Controller
{
    protected $controller,
        $tUserLogTab,
        $mUnitTypeTabs,
        $mUnitStatusTabs,
        $mUnitTabs,
        $mProjectTab;
    public function __construct(
        Controller $controller,
        MUnitTypeTabs $mUnitTypeTabs,
        MUnitStatusTabs $mUnitStatusTabs,
        MUnitTabs $mUnitTabs,
        MProjectTab $mProjectTab,
        TUserLogTab $tUserLogTab
    ) {
        $this->controller = $controller;
        $this->mUnitTypeTabs = $mUnitTypeTabs;
        $this->mUnitStatusTabs = $mUnitStatusTabs;
        $this->mProjectTab = $mProjectTab;
        $this->tUserLogTab = $tUserLogTab;
        $this->mUnitTabs = $mUnitTabs;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->successList(
            'INDEX UNIT',
            $this->mUnitTabs->search($request)->detail()->orderBy($request->key ?? 'id', $request->type ?? 'desc')->paginate(10),
            [
                [
                    "name" => "Blok",
                    "key" => "blok",
                    'type' => 'string',
                    'classNameRow' => 'text-center',
                    'className' => 'font-intersemibold text-center text-xl'
                ],
                [
                    'type' => 'custom',
                    'key' => 'type',
                    'name' => 'Type Unit',
                    'classNameRow' => 'text-start',
                ],
                [
                    "name" => "project",
                    "key" => "project.title",
                    'type' => 'string',
                    'classNameRow' => 'text-center',
                    'className' => 'text-center font-intersemibold'
                ],
                [
                    "name" => "Status Unit",
                    "key" => "status",
                    'type' => 'custom',
                    'classNameRow' => 'text-center',
                ],
                [
                    "type" => 'action',
                    "ability" => ["EDIT", "DELETE"]
                ]
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
                    "key" => 'blok',
                    "label" => "Blok Unit",
                    "type" => "text",
                    "blok" => null,
                    "isRequired" => true,
                    "placeholder" => "Masukan blok unit",
                ],
                [
                    "key" => 'm_project_tabs_id',
                    "label" => "Pilih Project",
                    "type" => "select",
                    "isRequired" => true,
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
                    "key" => 'm_unit_type_tabs_id',
                    "label" => "Pilih Type Unit",
                    "type" => "select",
                    "isRequired" => true,
                    "m_unit_type_tabs_id" => null,
                    "placeholder" => "Pilih Type Unit",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mUnitTypeTabs->get()
                    ]
                ],
                [
                    "key" => 'm_unit_status_tabs_id',
                    "label" => "Pilih Status Unit",
                    "type" => "select",
                    "isRequired" => true,
                    "m_unit_status_tabs_id" => null,
                    "placeholder" => "Pilih Status Unit",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mUnitStatusTabs->get()
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
            'blok' => 'required',
            'm_project_tabs_id' => 'required',
            'm_unit_status_tabs_id' => 'required',
            'm_unit_type_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $access = $this->mUnitTabs->create($request->all());
            $this->tUserLogTab->create([
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$UNIT,
                'm_action_tabs_id' => MActionTab::$ADD,
                'description' => "Tambah Unit Baru",
            ]);
            DB::commit();
            return $this->controller->resSuccess($access, [
                "title" => "Unit berhasil dibuat !",
                "body" => "Unit baru berhasil disimpan dan ditambahkan kedalam list"
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
        return $this->controller->resSuccess($this->mUnitTabs->where('m_project_tabs_id', $id)->search($request)->get());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->mUnitTabs->where('id', $id)->detail()->first();
        return $this->controller->resSuccess(
            array(
                [
                    "key" => 'blok',
                    "label" => "Blok Unit",
                    "type" => "text",
                    "blok" => $detail->blok,
                    "isRequired" => true,
                    "placeholder" => "Masukan blok unit",
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
                    "key" => 'm_unit_type_tabs_id',
                    "label" => "Pilih Type Unit",
                    "type" => "select",
                    "isRequired" => true,
                    "m_unit_type_tabs_id" => $detail->m_unit_type_tabs_id,
                    "placeholder" => "Pilih Type Unit",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mUnitTypeTabs->get()
                    ]
                ],
                [
                    "key" => 'm_unit_status_tabs_id',
                    "label" => "Pilih Status Unit",
                    "type" => "select",
                    "isRequired" => true,
                    "m_unit_status_tabs_id" => $detail->m_unit_status_tabs_id,
                    "placeholder" => "Pilih Status Unit",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mUnitStatusTabs->get()
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
            'blok' => 'required',
            'm_project_tabs_id' => 'required',
            'm_unit_status_tabs_id' => 'required',
            'm_unit_type_tabs_id' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $this->mUnitTabs->where('id', $id)->update($request->all());
            $this->tUserLogTab->create([
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$UNIT,
                'm_action_tabs_id' => MActionTab::$UPDATE,
                'description' => "Update Unit",
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
            ]);
            DB::commit();
            return $this->controller->resSuccess("UPDATED", [
                "title" => "Unit berhasil diupdate !",
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
            $this->mUnitTabs->where('id', $id)->delete();
            $this->tUserLogTab->create([
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$UNIT,
                'm_action_tabs_id' => MActionTab::$DELETE,
                'description' => "Hapus Unit",
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
            ]);
            DB::commit();
            return $this->controller->resSuccess(null, [
                "title" => "Unit berhasil dihapus !",
                "body" => "Semua informasi dan koneksi terhadap Unit tersebut berhasil dihapus"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }
}
