<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MProjectTab;
use Modules\Company\Models\MUnitStatusTabs;
use Modules\Company\Models\MUnitTypeTabs;
use Modules\Master\Models\MActionTab;
use Modules\Master\Models\MModuleTab;
use Modules\Users\Models\TUserLogTab;

class UnitTypeController extends Controller
{
    protected $controller,
        $tUserLogTab,
        $mUnitTypeTabs,
        $mUnitStatusTabs,
        $mProjectTab;
    public function __construct(
        Controller $controller,
        MUnitTypeTabs $mUnitTypeTabs,
        MUnitStatusTabs $mUnitStatusTabs,
        MProjectTab $mProjectTab,
        TUserLogTab $tUserLogTab
    ) {
        $this->controller = $controller;
        $this->mUnitTypeTabs = $mUnitTypeTabs;
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
            'INDEX UNIT TYPE',
            $this->mUnitTypeTabs->search($request)->detail()->orderBy($request->key ?? 'id', $request->type ?? 'desc')->paginate(10),
            [
                [
                    "name" => "Type Unit",
                    'useSort' => true,
                    'type' => 'array',
                    'classNameRow' => 'text-start',
                    'child' => array(
                        [
                            "type" => "string",
                            "key" => "title",
                            "className" => 'font-intersemibold text-base pb-1 mb-1 border-b border-blue-500'
                        ],
                        [
                            "type" => "string",
                            "key" => "descriptions",
                            'className' => 'text-xs italic'
                        ],
                    )
                ],
                [
                    "name" => "Project",
                    "key" => "project.title",
                    'type' => 'string',
                    'classNameRow' => 'text-start'
                ],
                [
                    "name" => "Harga Unit",
                    "key" => "price",
                    'type' => 'custom',
                ],
                [
                    "name" => "Tanah (P/L) m²",
                    "key" => "land",
                    'type' => 'custom',
                ],
                [
                    "name" => "Bangunan (P/L) m²",
                    "key" => "build",
                    'type' => 'custom',
                ],
                [
                    "name" => "Class",
                    "key" => "unit_class.title",
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
                    "key" => 'title',
                    "label" => "Type Unit",
                    "type" => "text",
                    "title" => null,
                    "isRequired" => true,
                    "placeholder" => "Masukan type unit",
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
                    "key" => 'm_unit_status_tabs_id',
                    "label" => "Pilih Status Unit",
                    "type" => "select",
                    "isRequired" => true,
                    'useClear' => true,
                    "m_unit_status_tabs_id" => null,
                    "placeholder" => "Pilih Status Unit",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mUnitStatusTabs->get()
                    ]
                ],
                [
                    "key" => 'm_unit_class_tabs_id',
                    "label" => "Pilih Class Unit",
                    "type" => "select",
                    "isRequired" => true,
                    'useClear' => true,
                    "m_unit_class_tabs_id" => null,
                    "placeholder" => "Pilih Class Unit",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => array(
                            ['id' => 1, 'title' => 'KPR'],
                            ['id' => 2, 'title' => 'Komersil'],
                        )
                    ]
                ],
                [
                    "key" => 'long_build',
                    "label" => "Panjang Bangunan (m)",
                    "type" => "number",
                    "long_build" => null,
                    "isRequired" => true,
                    "description" => 'Ukuran dalam meter',
                    "placeholder" => "Masukan Panjang Bangunan",
                ],
                [
                    "key" => 'width_build',
                    "label" => "Lebar Bangunan (m)",
                    "type" => "number",
                    "width_build" => null,
                    "isRequired" => true,
                    "description" => 'Ukuran dalam meter',
                    "placeholder" => "Masukan Lebar Bangunan",
                ],
                [
                    "key" => 'long_land',
                    "label" => "Panjang Tanah (m)",
                    "type" => "number",
                    "long_land" => null,
                    "isRequired" => true,
                    "description" => 'Ukuran dalam meter',
                    "placeholder" => "Masukan Panjang Tanah",
                ],
                [
                    "key" => 'width_land',
                    "label" => "Lebar Tanah (m)",
                    "type" => "number",
                    "isRequired" => true,
                    "width_land" => null,
                    "description" => 'Ukuran dalam meter',
                    "placeholder" => "Masukan Lebar Tanah",
                ],
                [
                    "key" => 'description',
                    "label" => "Deskripsi Type Unit",
                    "type" => "textarea",
                    "description" => null,
                    "placeholder" => "Masukan Deskripsi Type Unit",
                ],
                [
                    "key" => 'price',
                    "label" => "Harga Type Unit (Rp)",
                    "type" => "currency",
                    "price" => null,
                    "isRequired" => true,
                    "description" => 'Harga dalam Rupiah',
                    "placeholder" => "Masukan Harga Type Unit",
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
            'title' => 'required',
            'm_project_tabs_id' => 'required',
            'm_unit_status_tabs_id' => 'required',
            'm_unit_class_tabs_id' => 'required',
            'price' => 'required',
            'long_build' => 'required',
            'long_land' => 'required',
            'width_land' => 'required',
            'width_build' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $access = $this->mUnitTypeTabs->create($request->all());
            $this->tUserLogTab->create([
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$UNIT,
                'm_action_tabs_id' => MActionTab::$ADD,
                'description' => "Tambah Type Unit Baru",
            ]);
            DB::commit();
            return $this->controller->resSuccess($access, [
                "title" => "Type berhasil dibuat !",
                "body" => "Type baru berhasil disimpan dan ditambahkan kedalam list"
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
        return $this->controller->resSuccess($this->mUnitTypeTabs->where('m_project_tabs_id', $id)->search($request)->get());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->mUnitTypeTabs->where('id', $id)->detail()->first();
        return $this->controller->resSuccess(
            array(
                [
                    "key" => 'title',
                    "label" => "Type Unit",
                    "type" => "text",
                    "title" => $detail->title,
                    "isRequired" => true,
                    "placeholder" => "Masukan type unit",
                ],
                [
                    "key" => 'm_project_tabs_id',
                    "label" => "Pilih Project",
                    "type" => "select",
                    "isRequired" => true,
                    'useClear' => true,
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
                    "key" => 'm_unit_status_tabs_id',
                    "label" => "Pilih Status Unit",
                    "type" => "select",
                    "isRequired" => true,
                    'useClear' => true,
                    "m_unit_status_tabs_id" => $detail->m_unit_status_tabs_id,
                    "placeholder" => "Pilih Status Unit",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mUnitStatusTabs->get()
                    ]
                ],
                [
                    "key" => 'm_unit_class_tabs_id',
                    "label" => "Pilih Class Unit",
                    "type" => "select",
                    "isRequired" => true,
                    'useClear' => true,
                    "m_unit_class_tabs_id" => $detail->m_unit_class_tabs_id,
                    "placeholder" => "Pilih Class Unit",
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => array(
                            ['id' => 1, 'title' => 'KPR'],
                            ['id' => 2, 'title' => 'Komersil'],
                        )
                    ]
                ],
                [
                    "key" => 'long_build',
                    "label" => "Panjang Bangunan (m)",
                    "type" => "number",
                    "long_build" => $detail->long_build,
                    "isRequired" => true,
                    "description" => 'Ukuran dalam meter',
                    "placeholder" => "Masukan Panjang Bangunan",
                ],
                [
                    "key" => 'width_build',
                    "label" => "Lebar Bangunan (m)",
                    "type" => "number",
                    "width_build" => $detail->width_build,
                    "isRequired" => true,
                    "description" => 'Ukuran dalam meter',
                    "placeholder" => "Masukan Lebar Bangunan",
                ],
                [
                    "key" => 'long_land',
                    "label" => "Panjang Tanah (m)",
                    "type" => "number",
                    "long_land" => $detail->long_land,
                    "isRequired" => true,
                    "description" => 'Ukuran dalam meter',
                    "placeholder" => "Masukan Panjang Tanah",
                ],
                [
                    "key" => 'width_land',
                    "label" => "Lebar Tanah (m)",
                    "type" => "number",
                    "isRequired" => true,
                    "width_land" => $detail->width_land,
                    "description" => 'Ukuran dalam meter',
                    "placeholder" => "Masukan Lebar Tanah",
                ],
                [
                    "key" => 'descriptions',
                    "label" => "Deskripsi Type Unit",
                    "type" => "textarea",
                    "descriptions" => $detail->descriptions,
                    "placeholder" => "Masukan Deskripsi Type Unit",
                ],
                [
                    "key" => 'price',
                    "label" => "Harga Type Unit (Rp)",
                    "type" => "currency",
                    "price" => $detail->price,
                    "isRequired" => true,
                    "description" => 'Harga dalam Rupiah',
                    "placeholder" => "Masukan Harga Type Unit",
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
            'title' => 'required',
            'm_project_tabs_id' => 'required',
            'm_unit_status_tabs_id' => 'required',
            'm_unit_class_tabs_id' => 'required',
            'price' => 'required',
            'long_build' => 'required',
            'long_land' => 'required',
            'width_land' => 'required',
            'width_build' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $this->mUnitTypeTabs->where('id', $id)->update($request->all());
            $this->tUserLogTab->create([
                'm_user_tabs_id' => auth()->user()->id,
                'm_module_tabs_id' => MModuleTab::$UNIT,
                'm_action_tabs_id' => MActionTab::$UPDATE,
                'description' => "Update Type Unit",
                'm_company_tabs_id' => auth()->user()->m_company_tabs_id,
            ]);
            DB::commit();
            return $this->controller->resSuccess("UPDATED", [
                "title" => "Type Unit berhasil diupdate !",
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
            $this->mUnitTypeTabs->where('id', $id)->delete();
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
