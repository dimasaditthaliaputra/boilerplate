<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

use Exception;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\HakAkses\HakAksesService;
use App\Http\Requests\HakAkses\StoreHakAksesRequest;
use App\Http\Requests\HakAkses\UpdateHakAksesRequest;

class HakAksesController extends Controller
{
    protected $hakAksesService;

    public function __construct(HakAksesService $hakAksesService)
    {
        $this->hakAksesService = $hakAksesService;
    }

    public function index(Request $request)
    {
        $menus = Menu::all();

        if ($request->ajax()) {
            return $this->hakAksesService->dataTable();
        }

        return view('admin.setting.manajemen-hak-akses.index', compact('menus'));
    }

    public function store(StoreHakAksesRequest $request)
    {
        try {
            $this->hakAksesService->store($request);

            return ResponseHelper::jsonSuccess(null, 'Permissions berhasil dibuat dan menu telah diupdate');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = $this->hakAksesService->getPermissionDetail($id);

            return ResponseHelper::jsonSuccess($data, 'Detail permissions berhasil diambil');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function update(UpdateHakAksesRequest $request, $id)
    {
        try {
            $this->hakAksesService->update($request, $id);

            return ResponseHelper::jsonSuccess(null, 'Permissions berhasil diupdate dan menu telah disesuaikan');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->hakAksesService->destroy($id);

            return ResponseHelper::jsonSuccess(null, 'Permissions berhasil dihapus dan menu telah disesuaikan');
        } catch (Exception $e) {
            return ResponseHelper::jsonError($e->getMessage());
        }
    }
}
