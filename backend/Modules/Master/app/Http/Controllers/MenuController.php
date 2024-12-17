<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Master\Models\MMenuTab;
use Modules\Users\Models\TCompanyAdminTab;

class MenuController extends Controller
{
    protected $mMenuTab, $controller, $tCompanyAdminTab;
    public function __construct(
        Controller $controller,
        MMenuTab $mMenuTab,
        TCompanyAdminTab $tCompanyAdminTab
    ) {
        $this->controller = $controller;
        $this->mMenuTab = $mMenuTab;
        $this->tCompanyAdminTab = $tCompanyAdminTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // if Super Admin
        if ($this->tCompanyAdminTab->where('m_user_tabs_id', auth()->user()->id)->first()) {
            return $this->controller->resSuccess($this->mMenuTab->where('m_status_tabs_id', 1)->detail()->get());
        }
        return $this->controller->resSuccess($this->mMenuTab->detail()->get());
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
            'title' => 'required|unique:m_menu_tabs,title',
            'url' => 'required',
            'sequence' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->mMenuTab->create($request->all());
            DB::commit();
            return $this->controller->resSuccess('CREATE MENU');
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
            'title' => 'required|unique:m_menu_tabs,title',
            'url' => 'required',
            'sequence' => 'required',
            'm_status_tabs_id' => 'required',
            'parent_id' => 'required',
            'icon' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->mMenuTab->where('id', $id)->update([
                'title' => $request->title,
                'url' => $request->url,
                'sequence' => $request->sequence,
                'icon' => $request->icon,
                'm_status_tabs_id' => $request->m_status_tabs_id,
                'parent_id' => $request->parent_id,
            ]);
            DB::commit();
            return $this->controller->resSuccess('UPDATED MENU');
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
            $this->mMenuTab->where('id', $id)->delete();
            DB::commit();
            return $this->controller->resSuccess("DELETED MENU");
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(501, $th->getMessage());
        }
    }
}
