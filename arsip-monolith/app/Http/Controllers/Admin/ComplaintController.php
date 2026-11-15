<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateComplaintStatusRequest;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    /**
     * Display a listing of all complaints for admin and technicians.
     */
    public function index(Request $request): View
    {
        $query = Complaint::with(['user', 'lab', 'category', 'technician']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by lab
        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        // Filter by category ID or category name
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        } elseif ($request->filled('category')) {
            $cat = $request->category;
            $query->whereHas('category', function ($q) use ($cat) {
                $q->where('nama_kategori', 'LIKE', "%{$cat}%");
            });
        }

        // Filter by assigned technician
        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        // Filter: technician can quick-filter "assigned to me"
        if ($request->boolean('my_tasks') && $request->user()->isTechnician()) {
            $query->where('technician_id', $request->user()->id);
        }

        // Search: report number, reporter name, NIM, description, or PC number
        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanId = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $cleanId) {
                if (! empty($cleanId)) {
                    $q->orWhere('id', (int) $cleanId);
                }
                $q->orWhere('deskripsi_masalah', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_pc', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('nim_nip', 'LIKE', "%{$search}%");
                  });
            });
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();

        $labs = Lab::orderBy('nama_lab')->get();
        $categories = Category::orderBy('nama_kategori')->get();
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();

        return view('admin.complaints.index', compact('complaints', 'labs', 'categories', 'technicians'));
    }

    /**
     * Display detail of a complaint and management controls.
     */
    public function show(Complaint $complaint): View
    {
        $complaint->load(['user', 'lab', 'category', 'technician', 'histories.user']);

        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();

        return view('admin.complaints.show', compact('complaint', 'technicians'));
    }

    /**
     * Update complaint status, assign technician, and provide feedback.
     * Wrapped in DB::transaction() for data integrity.
     * Records audit trail in complaint_histories table.
     */
    public function update(UpdateComplaintStatusRequest $request, Complaint $complaint): RedirectResponse
    {
        // Enforce authorization policy:
        // Admin can update all; Technician can only update complaints assigned to them
        Gate::authorize('update', $complaint);

        $validated = $request->validated();
        $user = $request->user();

        // Technician cannot reassign technician_id, only Admin can assign
        if (! $user->isAdmin()) {
            unset($validated['technician_id']);
        }

        // Simpan status lama sebelum update
        $oldStatus = $complaint->status;

        DB::transaction(function () use ($complaint, $validated, $user, $oldStatus) {
            $complaint->update($validated);

            // Catat audit trail jika status berubah
            if ($oldStatus !== $complaint->status) {
                ComplaintHistory::create([
                    'complaint_id' => $complaint->id,
                    'user_id'      => $user->id,
                    'status_from'  => $oldStatus,
                    'status_to'    => $complaint->status,
                    'catatan'      => $validated['tanggapan'] ?? null,
                    'created_at'   => now(),
                ]);
            }
        });

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Status laporan ' . $complaint->report_number . ' berhasil diperbarui.');
    }
}
