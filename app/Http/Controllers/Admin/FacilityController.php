<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityRequest;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Lab;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(Request $request): View
    {
        $query = Facility::with(['lab', 'category']);

        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nama_fasilitas', 'LIKE', "%{$search}%");
        }

        $facilities = $query->latest()->paginate(15)->withQueryString();

        $labs = Lab::orderBy('nama_lab')->get();
        $categories = Category::orderBy('nama_kategori')->get();

        return view('admin.facilities.index', compact('facilities', 'labs', 'categories'));
    }

    public function create(): View
    {
        $labs = Lab::orderBy('nama_lab')->get();
        $categories = Category::orderBy('nama_kategori')->get();

        return view('admin.facilities.create', compact('labs', 'categories'));
    }

    public function store(FacilityRequest $request): RedirectResponse
    {
        $facility = Facility::create($request->validated());

        return redirect()->route('admin.facilities.index')
            ->with('success', "Fasilitas '{$facility->nama_fasilitas}' berhasil ditambahkan.");
    }

    public function edit(Facility $facility): View
    {
        $labs = Lab::orderBy('nama_lab')->get();
        $categories = Category::orderBy('nama_kategori')->get();

        return view('admin.facilities.edit', compact('facility', 'labs', 'categories'));
    }

    public function update(FacilityRequest $request, Facility $facility): RedirectResponse
    {
        $facility->update($request->validated());

        return redirect()->route('admin.facilities.index')
            ->with('success', "Fasilitas '{$facility->nama_fasilitas}' berhasil diperbarui.");
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $nama = $facility->nama_fasilitas;
        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', "Fasilitas '{$nama}' berhasil dihapus.");
    }
}
