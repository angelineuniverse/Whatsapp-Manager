<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Master\Models\MAccessTab;

class AccessController extends Controller
{
    protected $controller;
    protected $mAccessTab;
    public function __construct(
        Controller $controller,
        MAccessTab $mAccessTab
    ) {
        $this->controller = $controller;
        $this->mAccessTab = $mAccessTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->successList(
            'INDEX ACCESS',
            $this->mAccessTab->search($request)->detail()->paginate(10),
            []
        );
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
            'title' => 'required|unique:m_access_tabs,title',
            'color' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['m_company_tabs_id'] = auth()->user()->m_company_tabs_id;
            $access = $this->mAccessTab->create($request->all());
            DB::commit();
            return $this->controller->resSuccess($access);
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
        return $this->controller->resSuccess($this->mAccessTab->where('m_company_tabs_id', $id)->get());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->mAccessTab->where('id', $id)->delete();
            DB::commit();
            return $this->controller->resSuccess(null);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }
}
