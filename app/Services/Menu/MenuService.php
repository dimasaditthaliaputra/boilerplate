<?php

namespace App\Services\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Http\Requests\Menu\UpdateMenuRequest;
use Yajra\DataTables\Facades\DataTables;

class MenuService
{
    public function dataTable()
    {
        $data = Menu::with('parent')->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $showUrl = route('manajemen-menu.show', $row->id);
                $deleteUrl = route('manajemen-menu.destroy', $row->id);

                $btn = '<div class="d-flex justify-content-center gap-2">';
                $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Edit</button>';
                $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button></form>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function findById($id)
    {
        $data = Menu::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan.',
            ], 404);
        }

        return $data;
    }

    public function store(StoreMenuRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $db = [
                'name' => $request->name,
                'icon' => $request->icon,
                'route' => $request->route,
                'url' => $request->url,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'is_active' => $request->is_active === 'true',
            ];

            Menu::create($db);
        });
    }

    public function update(UpdateMenuRequest $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $data = $this->findById($id);

            $db = [
                'name' => $request->name,
                'icon' => $request->icon,
                'route' => $request->route,
                'url' => $request->url,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'is_active' => $request->is_active === 'true',
            ];

            $data->update($db);
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
