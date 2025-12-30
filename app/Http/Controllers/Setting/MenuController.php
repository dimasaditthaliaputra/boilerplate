<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

use Exception;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\Menu\MenuService;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Http\Requests\Menu\UpdateMenuRequest;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        $parents = $parents = Menu::whereNull('parent_id')
            ->orderBy('order')
            ->get();

        $roles = Role::all();

        if ($request->ajax()) {
            return $this->menuService->dataTable();
        }

        return view('admin.setting.manajemen-menu.index', compact('parents', 'roles'));
    }

    public function store(StoreMenuRequest $request)
    {
        try {
            $this->menuService->store($request);

            return ResponseHelper::jsonSuccess(null, 'Menu berhasil ditambahkan.');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = $this->menuService->findById($id);

            return ResponseHelper::jsonSuccess($data, 'Data menu berhasil diambil');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function update(UpdateMenuRequest $request, $id)
    {
        try {
            $this->menuService->update($request, $id);

            return ResponseHelper::jsonSuccess(null, 'Menu berhasil diperbarui.');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->menuService->destroy($id);

            return ResponseHelper::jsonSuccess(null, 'Menu berhasil dihapus.');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }
}
