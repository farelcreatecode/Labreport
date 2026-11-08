<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Lab;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    /**
     * Display a listing of complaints submitted by the authenticated user.
     */
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $query = Complaint::with(['lab', 'category', 'technician'])
            ->where('user_id', $userId);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search query: report number, facility/category, lab, description, or pc number
        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanId = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $cleanId) {
                if (! empty($cleanId)) {
                    $q->orWhere('id', (int) $cleanId);
                }
                $q->orWhere('deskripsi_masalah', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_pc', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', fn ($c) => $c->where('nama_kategori', 'LIKE', "%{$search}%"))
                  ->orWhereHas('lab', fn ($l) => $l->where('nama_lab', 'LIKE', "%{$search}%"));
            });
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();

        return view('pelapor.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new complaint.
     */
    public function create(): View
    {
        $labs = Lab::orderBy('nama_lab')->get();
        $categories = Category::orderBy('nama_kategori')->get();

        return view('pelapor.complaints.create', compact('labs', 'categories'));
    }

    /**
     * Store a newly created complaint in storage.
     */
    public function store(StoreComplaintRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('complaints', 'public');
        }

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'pending';

        $complaint = Complaint::create($validated);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Laporan kerusakan fasilitas berhasil dikirim dengan nomor ' . $complaint->report_number . '.');
    }

    /**
     * Display the specified complaint detail.
     */
    public function show(Complaint $complaint): View
    {
        // Enforce policy: pelapor cannot view another user's complaint
        Gate::authorize('view', $complaint);

        $complaint->load(['lab', 'category', 'technician', 'user']);

        return view('pelapor.complaints.show', compact('complaint'));
    }
}
