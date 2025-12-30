<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\Role\RoleService;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $permissions = Permission::all();

        if ($request->ajax()) {
            return $this->roleService->dataTable();
        }

        return view('admin.setting.manajemen-role.index', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $this->roleService->store($request);

            return ResponseHelper::jsonSuccess(null, 'Role berhasil ditambahkan.');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = $this->roleService->findById($id);

            return ResponseHelper::jsonSuccess($data, 'Data role berhasil diambil.');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            $this->roleService->update($request, $id);

            return ResponseHelper::jsonSuccess(null, 'Role berhasil diperbarui.');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->roleService->destroy($id);

            return ResponseHelper::jsonSuccess(null, 'Role berhasil dihapus.');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }
}
