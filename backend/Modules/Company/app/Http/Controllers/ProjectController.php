<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MProjectTab;
use Modules\Master\Models\MCodeTab;

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
    public function index()
    {
        return view('master::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validing($request->all(), [
            'title' => 'required|unique:m_project_tabs,title',
            'description' => 'required|min:8',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = MCodeTab::generateCode('USR');
            $request['m_company_tabs_id'] = auth()->user()->m_company_tabs_id;
            $project = $this->mProjectTab->create($request->all());
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR_' . $request->code . '_' . $file->getClientOriginalName();
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
        return view('master::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validing($request->all(), [
            'title' => 'required|unique:m_project_tabs,title',
            'description' => 'required|min:8',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $project = $this->mProjectTab->where('id', $id)->first();
            $project->update($request->all());
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR_' . $request->code . '_' . $file->getClientOriginalName();
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
            $project->update($request->all());
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AVR_' . $request->code . '_' . $file->getClientOriginalName();
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
}
