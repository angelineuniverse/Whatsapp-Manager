<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\MCompanyTab;
use Modules\Master\Models\MCodeTab;

class CompanyController extends Controller
{
    protected $controller;
    protected $mCompanyTab;
    public function __construct(
        Controller $controller,
        MCompanyTab $mCompanyTab
    ) {
        $this->controller = $controller;
        $this->mCompanyTab = $mCompanyTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->successList(
            'INDEX COMPANY',
            $this->mCompanyTab->search($request)->detail()->paginate(10),
            []
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validing($request->all(), [
            'name' => 'required|unique:m_company_tabs,name',
            'email' => 'required|email|unique:m_company_tabs,email'
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = MCodeTab::generateCode('CPY');
            $request['m_status_tabs_id'] = 3;
            $company = $this->mCompanyTab->create($request->all());
            DB::commit();
            return $this->controller->resSuccess($company);
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
        return $this->controller->resSuccess($this->mCompanyTab->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('company::edit');
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
            $this->mCompanyTab->where('id', $id)->delete();
            DB::commit();
            return $this->controller->resSuccess(null);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }
}
