<?php

namespace App\Services\Role;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleService
{
    public function dataTable()
    {
        $data = Role::with('permissions')->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('permissions', function ($row) {
                $groupedPermissions = $row->permissions->groupBy(function ($permission) {
                    return explode('.', $permission->name)[0];
                });

                $result = '';
                foreach ($groupedPermissions as $module => $permissions) {
                    $badges = [];
                    foreach ($permissions as $permission) {
                        $action = explode('.', $permission->name)[1];

                        $badgeClass = match ($action) {
                            'show' => 'bg-primary',
                            'create' => 'bg-success',
                            'update' => 'bg-warning',
                            'delete' => 'bg-danger',
                            default => 'bg-info'
                        };

                        $badges[] = '<span class="badge ' . $badgeClass . ' me-1">' . $action . '</span>';
                    }

                    $result .= '<div class="mb-1"><strong>' . e($module) . ':</strong> ' . implode(' ', $badges) . '</div>';
                }

                return $result;
            })
            ->addColumn('action', function ($row) {
                $showUrl = route('manajemen-role.show', $row->id);
                $deleteUrl = route('manajemen-role.destroy', $row->id);

                $btn = '<div class="d-flex justify-content-center gap-2">';
                $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Edit</button>';
                $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button></form>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['permissions', 'action'])
            ->make(true);
    }

    public function findById($id)
    {
        $data = Role::with('permissions')->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan.',
            ], 404);
        }

        return $data;
    }

    public function store(StoreRoleRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $db = [
                'name' => $request->name,
                'guard_name' => 'web',
            ];

            $role = Role::create($db);

            if ($request->has('permission_name') && !empty($request->permission_name)) {
                $role->givePermissionTo($request->permission_name);
            }
        });
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $role = $this->findById($id);

            $role->name = $request->name;
            $role->save();

            if ($request->has('permission_name') && !empty($request->permission_name)) {
                $role->syncPermissions($request->permission_name);
            } else {
                $role->syncPermissions([]);
            }
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $data = $this->findById($id);

            $data->delete();
        });
    }
}
