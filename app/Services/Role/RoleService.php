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
                            'show' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400',
                            'create' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                            'update' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                            'delete' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                            default => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400'
                        };

                        $badges[] = '<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ' . $badgeClass . '">' . $action . '</span>';
                    }

                    $result .= '<div class="mb-1"><span class="font-semibold text-gray-700 dark:text-gray-300">' . e($module) . ':</span> ' . implode(' ', $badges) . '</div>';
                }

                return $result;
            })
            ->addColumn('action', function ($row) {
                $showUrl = route('manajemen-role.show', $row->id);
                $deleteUrl = route('manajemen-role.destroy', $row->id);

                $btn = '<div class="flex items-center justify-center gap-2">';
                $btn .= '<button class="inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-sm font-medium text-white bg-brand-500 hover:bg-brand-600 transition-colors edit-button" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Edit</button>';
                $btn .= '<form action="' . e($deleteUrl) . '" method="POST" class="inline">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-sm font-medium text-white bg-error-500 hover:bg-error-600 transition-colors">Hapus</button></form>';
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
