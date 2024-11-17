<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MProjectTab;

class ProjectController extends Controller
{
    protected $controller;
    protected $mProjectTab;
    public function __construct(
        MProjectTab $mProjectTab,
        Controller $controller,
    ) {
        $this->controller = $controller;
        $this->mProjectTab = $mProjectTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->successList(
            "LIST PROJECT",
            $this->mProjectTab->where('m_company_tabs_id', auth()->user()->m_company_tabs_id)->query($request)->orderBy('id', 'desc')->paginate(10),
            array(
                [
                    'name' => 'Project',
                    'type' => 'array',
                    'key' => 'title',
                    'useSort' => true,
                    "classNameRow" => "text-start",
                    'child' => array(
                        [
                            "type" => "string",
                            "key" => "title",
                            "className" => 'font-intersemibold pb-1 mb-1 border-b border-blue-500'
                        ],
                        [
                            "type" => "string",
                            "key" => "address",
                            'className' => 'text-xs'
                        ],
                    )
                ],
                [
                    "name" => "Avatar",
                    "type" => "custom",
                    "key" => "avatar",
                ],
                [
                    "name" => "Deskripsi",
                    "type" => "string",
                    "classNameRow" => "text-start text-xs",
                    "key" => "descriptions",
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
                            'key' => 'activated',
                            'show_by' => 'm_status_tabs_id',
                            'title' => 'Aktivasi',
                            'theme' => 'primary',
                            'show_value' => [2]
                        ],
                        [
                            'key' => 'not_activated',
                            'show_by' => 'm_status_tabs_id',
                            'title' => 'Deaktivasi',
                            'theme' => 'warning',
                            'show_value' => [1]
                        ],
                        [
                            'key' => 'show',
                            'show_by' => 'm_status_tabs_id',
                            'title' => 'Ubah',
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
                    "label" => "Nama Project",
                    "key" => 'title',
                    'isRequired' => true,
                    'title' =>  null,
                    'placeholder' => 'Masukan nama project'
                ],
                [
                    "type" => "text",
                    "label" => "Alamat",
                    "key" => 'address',
                    'isRequired' => true,
                    'address' =>  null,
                    'placeholder' => 'Masukan alamat project'
                ],
                [
                    "type" => "textarea",
                    "label" => "Deskripsi",
                    "key" => 'descriptions',
                    'isRequired' => true,
                    'descriptions' =>  null,
                    'placeholder' => 'Masukan deskripsi project'
                ],
                [
                    "type" => "upload",
                    "label" => "Avatar Project",
                    "key" => 'avatar',
                    'isRequired' => true,
                    'avatar' =>  null,
                    'accept' => 'image/png,image/jpeg,image/jpg',
                    'description' => "Supported PNG/JPEG/JPG ( Max 5Mb )"
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
            'title' => 'required|unique:m_project_tabs,title',
            'descriptions' => 'required|min:8',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['m_company_tabs_id'] = $request->m_company_tabs_id ?? auth()->user()->m_company_tabs_id;
            $request["m_status_tabs_id"] = 2;
            $project = $this->mProjectTab->create($request->all());
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR' . $project->id . '_' . $file->getClientOriginalName();
                $file->move(public_path('avatar'), $filename);
                $project->update(['avatar' => $filename]);
            }
            DB::commit();
            return $this->controller->resSuccess("CREATED", [
                'title' => 'Project berhasil dibuat',
                'body' => 'Project Property anda yang baru berhasil disimpan di system',
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
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->mProjectTab->where('id', $id)->query()->first();
        return $this->controller->resSuccess(
            array(
                [
                    "type" => "text",
                    "label" => "Nama Project",
                    "key" => 'title',
                    'isRequired' => true,
                    'title' =>  $detail->title,
                    'placeholder' => 'Masukan nama project'
                ],
                [
                    "type" => "text",
                    "label" => "Alamat",
                    "key" => 'address',
                    'isRequired' => true,
                    'address' =>  $detail->address,
                    'placeholder' => 'Masukan alamat project'
                ],
                [
                    "type" => "textarea",
                    "label" => "Deskripsi",
                    "key" => 'descriptions',
                    'isRequired' => true,
                    'descriptions' =>  $detail->descriptions,
                    'placeholder' => 'Masukan deskripsi project'
                ],
                [
                    "type" => "upload",
                    "label" => "Avatar Project",
                    "key" => 'avatar',
                    'isRequired' => true,
                    'avatar' =>  $detail->avatar,
                    'filename' => $detail->avatar,
                    'preview_action' => 'avatar',
                    'accept' => 'image/png,image/jpeg,image/jpg',
                    'description' => "Supported PNG/JPEG/JPG ( Max 5Mb )"
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
            'title' => 'required|unique:m_project_tabs,title',
            'descriptions' => 'required|min:8',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $project = $this->mProjectTab->where('id', $id)->first();
            $project->update($request->all());
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR' . $project->id . '_' . $file->getClientOriginalName();
                $this->controller->unlink_filex('avatar', $project->avatar);
                $file->move(public_path('avatar'), $filename);
                $project->update(['avatar' => $filename]);
            }
            DB::commit();
            return $this->controller->resSuccess("CREATED", [
                'title' => 'Project berhasil diubah',
                'body' => 'Project Property anda yang baru berhasil disimpan di system',
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
            $project = $this->mProjectTab->where('id', $id)->first();
            $this->controller->unlink_filex('avatar', $project->avatar);
            $project->delete();
            DB::commit();
            return $this->controller->resSuccess("DELETED", [
                'title' => 'Project berhasil dihapus',
                'body' => 'Project Property anda yang berhasil dihapus di system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }

    public function changeStatus($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $this->mProjectTab->where('id', $id)->update($request->all());
            DB::commit();
            return $this->controller->resSuccess("CREATED", [
                'title' => 'Status Project berhasil diubah',
                'body' => 'Status Project Property anda berhasil disimpan di system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }
}
