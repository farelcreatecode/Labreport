<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabRequest;
use App\Models\Lab;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LabController extends Controller
{
    public function index(): View
    {
        $labs = Lab::withCount(['complaints', 'facilities'])->latest()->paginate(10);

        return view('admin.labs.index', compact('labs'));
    }

    public function create(): View
    {
        return view('admin.labs.create');
    }

    public function store(LabRequest $request): RedirectResponse
    {
        $lab = Lab::create($request->validated());

        return redirect()->route('admin.labs.index')
            ->with('success', "Laboratorium '{$lab->nama_lab}' berhasil ditambahkan.");
    }

    public function edit(Lab $lab): View
    {
        return view('admin.labs.edit', compact('lab'));
    }

    public function update(LabRequest $request, Lab $lab): RedirectResponse
    {
        $lab->update($request->validated());

        return redirect()->route('admin.labs.index')
            ->with('success', "Data laboratorium '{$lab->nama_lab}' berhasil diperbarui.");
    }

    public function destroy(Lab $lab): RedirectResponse
    {
        $nama = $lab->nama_lab;
        $lab->delete();

        return redirect()->route('admin.labs.index')
            ->with('success', "Laboratorium '{$nama}' berhasil dihapus.");
    }
}
