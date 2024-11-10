<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MRolesTab;

class RolesController extends Controller
{
    protected $controller;
    protected $mRolesTab;
    public function __construct(
        Controller $controller,
        MRolesTab $mRolesTab
    ) {
        $this->controller = $controller;
        $this->mRolesTab = $mRolesTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->successList(
            'INDEX ROLES',
            $this->mRolesTab->search($request)->detail()->paginate(10),
            [
                ["name" => "Nama Roles", "key" => "title", 'type' => 'string'],
                ["name" => "Parent Roles", "key" => "parent.title", 'type' => 'string', 'className' => 'text-center',],
                ["name" => "Total Pengguna", "key" => "users_count", 'type' =>
                'string', 'className' => 'text-center', 'classNameRow' => 'text-center'],
                ["name" => "Color", "key" => "color", 'type' => 'custom', 'classNameRow' => 'text-center'],
                ["type" => 'action', "ability" => ["SHOW", "DELETE"]]
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
            $request['m_company_tabs_id'] = auth()->user()->m_company_tabs_id;
            $access = $this->mRolesTab->create($request->all());
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
    public function show($id)
    {
        return $this->controller->resSuccess($this->mRolesTab->where('m_company_tabs_id', $id)->get());
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
                    "placeholder" => $detail->parent->title ?? null,
                    "parent_id" => $detail->parent_id ?? null,
                    "description" => 'Kosongkan apabila tidak memiliki parent Roles',
                    'list' => [
                        'keyValue' => 'id',
                        'keyOption' => 'title',
                        'options' => $this->mRolesTab->get()
                    ]
                ],
                [
                    "key" => 'color',
                    "label" => "Warna Roles",
                    "type" => "select",
                    "isRequired" => true,
                    "color" => $detail->color,
                    "placeholder" => $detail->color,
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
