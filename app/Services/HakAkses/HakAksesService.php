<?php

namespace App\Services\HakAkses;

use Exception;
use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\HakAkses\StoreHakAksesRequest;
use App\Http\Requests\HakAkses\UpdateHakAksesRequest;
use Yajra\DataTables\Facades\DataTables;

class HakAksesService
{
    public function dataTable()
    {
        $permissions = Permission::all();

        $dotPermissions = $permissions->filter(fn($p) => Str::contains($p->name, '.'));
        $standalonePermissions = $permissions->filter(fn($p) => !Str::contains($p->name, '.'));

        $groupedDotPermissions = $dotPermissions->groupBy(fn($item) => Str::before($item->name, '.'))
            ->map(function ($group, $baseName) {
                return (object) [
                    'id' => $group->first()->id,
                    'group_ids' => $group->pluck('id')->toArray(),
                    'name' => $baseName,
                    'guard_name' => $group->first()->guard_name,
                    'actions' => $group->map(fn($permission) => Str::after($permission->name, '.'))->toArray(),
                ];
            });

        $groupedStandalonePermissions = $standalonePermissions->map(function ($permission) {
            return (object) [
                'id' => $permission->id,
                'group_ids' => [$permission->id],
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'actions' => [],
            ];
        });

        $data = $groupedDotPermissions->merge($groupedStandalonePermissions)->values();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('access', function ($row) {
                if (empty($row->actions)) {
                    return '<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">Akses Penuh</span>';
                }

                $badges = collect($row->actions)->map(function ($action) {
                    $color = match ($action) {
                        'show' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400',
                        'create' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                        'update' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                        'delete' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                        'export' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                        'import' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                        'approve' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                        'report' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400',
                        default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                    };
                    return "<span class='inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {$color}'>" . ucfirst($action) . "</span>";
                })->implode(' ');

                return $badges;
            })
            ->addColumn('action', function ($row) {
                $showUrl = route('manajemen-hak-akses.show', $row->id);
                $deleteUrl = route('manajemen-hak-akses.destroy', $row->id);

                $btn = '<div class="flex items-center justify-center gap-2">';
                $btn .= '<button class="inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-sm font-medium text-white bg-brand-500 hover:bg-brand-600 transition-colors edit-button" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Edit</button>';
                $btn .= '<form action="' . e($deleteUrl) . '" method="POST" class="inline">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-sm font-medium text-white bg-error-500 hover:bg-error-600 transition-colors">Hapus</button></form>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['access', 'action'])
            ->make(true);
    }
    
    public function getPermissionDetail($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            throw new Exception("Permission tidak ditemukan.");
        }

        $baseName = Str::contains($permission->name, '.')
            ? Str::before($permission->name, '.')
            : $permission->name;

        $groupPermissions = Str::contains($permission->name, '.')
            ? Permission::where('name', 'like', $baseName . '.%')->get()
            : collect([$permission]);

        $actions = $groupPermissions->map(function ($p) use ($baseName) {
            return Str::contains($p->name, '.')
                ? Str::after($p->name, $baseName . '.')
                : $p->name;
        })->filter()->values()->toArray();

        $menu = Menu::where('permission', $baseName . '.show')->first();

        return [
            'name' => $baseName,
            'id' => $permission->id,
            'actions' => $actions,
            'menu' => $menu ? $menu->name : null,
        ];
    }

    public function store(StoreHakAksesRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $moduleName = Str::slug($request->name);
            $permissions = [];

            foreach ($request->access as $access) {
                $permissionName = $moduleName . '.' . $access;

                if (!Permission::where('name', $permissionName)->exists()) {
                    $permission = Permission::create(['name' => $permissionName, 'guard_name' => 'web']);
                    $permissions[] = $permission;
                }
            }

            if ($request->menu && in_array('show', $request->access)) {
                $menu = Menu::where('name', $request->menu)->first();
                if ($menu) {
                    $menu->update(['permission' => $moduleName . '.show']);
                }
            }
        });
    }

    public function update(UpdateHakAksesRequest $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $permission = Permission::findOrFail($id);

            $oldBaseName = Str::contains($permission->name, '.')
                ? Str::before($permission->name, '.')
                : $permission->name;

            $newBaseName = Str::slug($request->name);
            $existingPermissions = Permission::where('name', 'like', $oldBaseName . '.%')->get();

            $oldShowPermission = $oldBaseName . '.show';
            $oldMenu = Menu::where('permission', $oldShowPermission)->first();

            if ($oldBaseName !== $newBaseName) {
                foreach ($existingPermissions as $existingPermission) {
                    $action = Str::after($existingPermission->name, $oldBaseName . '.');
                    $existingPermission->update([
                        'name' => $newBaseName . '.' . $action
                    ]);
                }

                if ($oldMenu) {
                    $oldMenu->update(['permission' => $newBaseName . '.show']);
                }
            }

            $currentActions = $existingPermissions->map(function ($p) use ($newBaseName) {
                return Str::after($p->name, $newBaseName . '.');
            })->toArray();

            $desiredActions = $request->access;

            $actionsToDelete = array_diff($currentActions, $desiredActions);
            foreach ($actionsToDelete as $action) {
                Permission::where('name', $newBaseName . '.' . $action)->delete();

                if ($action === 'show') {
                    $menuToUpdate = Menu::where('permission', $newBaseName . '.show')->first();
                    if ($menuToUpdate) {
                        $menuToUpdate->update(['permission' => null]);
                    }
                }
            }

            $actionsToAdd = array_diff($desiredActions, $currentActions);
            foreach ($actionsToAdd as $action) {
                $permissionName = $newBaseName . '.' . $action;
                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'web'
                    ]);
                }

                if ($action === 'show') {
                    $newMenu = Menu::where('name', $request->menu)->first();
                    if ($newMenu) {
                        $newMenu->update(['permission' => $newBaseName . '.show']);
                    }
                }
            }

            if (in_array('show', $desiredActions)) {
                $currentMenu = Menu::where('permission', $newBaseName . '.show')->first();
                $newMenu = Menu::where('name', $request->menu)->first();

                if ($currentMenu && $newMenu && $currentMenu->id !== $newMenu->id) {
                    $currentMenu->update(['permission' => null]);
                    $newMenu->update(['permission' => $newBaseName . '.show']);
                } elseif (!$currentMenu && $newMenu) {
                    $newMenu->update(['permission' => $newBaseName . '.show']);
                }
            }
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $permission = Permission::findOrFail($id);

            $baseName = Str::contains($permission->name, '.')
                ? Str::before($permission->name, '.')
                : $permission->name;

            $groupPermissions = Permission::where('name', 'like', $baseName . '.%')->get();

            $showPermission = $baseName . '.show';
            $menu = Menu::where('permission', $showPermission)->first();
            if ($menu) {
                $menu->update(['permission' => null]);
            }

            foreach ($groupPermissions as $groupPermission) {
                $groupPermission->delete();
            }
        });
    }
}
