<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Laboratory;
use App\Models\Technician;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminMasterController extends Controller
{
    // ==========================================
    // LABORATORIES
    // ==========================================
    public function listLaboratories(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => Laboratory::withCount(['facilities', 'reports'])->orderBy('nama_lab')->get(),
        ]);
    }

    public function storeLaboratory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_lab'  => ['required', 'string', 'max:150'],
            'lokasi'    => ['nullable', 'string', 'max:255'],
            'kapasitas' => ['nullable', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $lab = Laboratory::create($request->all());

        return response()->json(['success' => true, 'message' => 'Laboratorium berhasil ditambahkan.', 'data' => $lab], 201);
    }

    public function updateLaboratory(Request $request, int $id): JsonResponse
    {
        $lab = Laboratory::find($id);
        if (! $lab) return response()->json(['success' => false, 'message' => 'Lab tidak ditemukan.'], 404);

        $lab->update($request->all());
        return response()->json(['success' => true, 'message' => 'Laboratorium berhasil diperbarui.', 'data' => $lab]);
    }

    public function destroyLaboratory(int $id): JsonResponse
    {
        $lab = Laboratory::find($id);
        if (! $lab) return response()->json(['success' => false, 'message' => 'Lab tidak ditemukan.'], 404);

        $lab->delete();
        return response()->json(['success' => true, 'message' => 'Laboratorium berhasil dihapus.']);
    }

    // ==========================================
    // FACILITIES
    // ==========================================
    public function listFacilities(Request $request): JsonResponse
    {
        $query = Facility::with('laboratory');
        if ($request->filled('laboratory_id')) {
            $query->where('laboratory_id', $request->laboratory_id);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->orderBy('nama_fasilitas')->get(),
        ]);
    }

    public function storeFacility(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'laboratory_id'  => ['required', 'exists:laboratories,id'],
            'nama_fasilitas' => ['required', 'string', 'max:150'],
            'kategori'       => ['required', 'in:hardware,software,jaringan'],
            'kondisi'        => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'nomor_seri'     => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $facility = Facility::create($request->all());
        return response()->json(['success' => true, 'message' => 'Fasilitas berhasil ditambahkan.', 'data' => $facility], 201);
    }

    public function updateFacility(Request $request, int $id): JsonResponse
    {
        $facility = Facility::find($id);
        if (! $facility) return response()->json(['success' => false, 'message' => 'Fasilitas tidak ditemukan.'], 404);

        $facility->update($request->all());
        return response()->json(['success' => true, 'message' => 'Fasilitas berhasil diperbarui.', 'data' => $facility]);
    }

    public function destroyFacility(int $id): JsonResponse
    {
        $facility = Facility::find($id);
        if (! $facility) return response()->json(['success' => false, 'message' => 'Fasilitas tidak ditemukan.'], 404);

        $facility->delete();
        return response()->json(['success' => true, 'message' => 'Fasilitas berhasil dihapus.']);
    }

    // ==========================================
    // TECHNICIANS (Bukan User Login, Pekerja Lapangan)
    // ==========================================
    public function listTechnicians(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => Technician::withCount('reports')->orderBy('nama')->get(),
        ]);
    }

    public function storeTechnician(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama'         => ['required', 'string', 'max:150'],
            'kontak'       => ['nullable', 'string', 'max:50'],
            'spesialisasi' => ['required', 'string', 'max:100'],
            'status'       => ['required', 'in:aktif,nonaktif'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $tech = Technician::create($request->all());
        return response()->json(['success' => true, 'message' => 'Data teknisi berhasil ditambahkan.', 'data' => $tech], 201);
    }

    public function updateTechnician(Request $request, int $id): JsonResponse
    {
        $tech = Technician::find($id);
        if (! $tech) return response()->json(['success' => false, 'message' => 'Teknisi tidak ditemukan.'], 404);

        $tech->update($request->all());
        return response()->json(['success' => true, 'message' => 'Data teknisi berhasil diperbarui.', 'data' => $tech]);
    }

    public function destroyTechnician(int $id): JsonResponse
    {
        $tech = Technician::find($id);
        if (! $tech) return response()->json(['success' => false, 'message' => 'Teknisi tidak ditemukan.'], 404);

        $tech->delete();
        return response()->json(['success' => true, 'message' => 'Data teknisi berhasil dihapus.']);
    }
}
