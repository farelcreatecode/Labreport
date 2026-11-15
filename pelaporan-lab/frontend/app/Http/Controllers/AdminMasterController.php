<?php

namespace App\Http\Controllers;

use App\Services\GatewayClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMasterController extends Controller
{
    protected GatewayClient $gateway;

    public function __construct(GatewayClient $gateway)
    {
        $this->gateway = $gateway;
    }

    // ==========================================
    // LABORATORIES
    // ==========================================
    public function laboratories(): View
    {
        $res = $this->gateway->get('/admin/laboratories');
        $laboratories = $res->json('data') ?? [];
        return view('admin.labs.index', compact('laboratories'));
    }

    public function createLaboratory(): View
    {
        return view('admin.labs.create');
    }

    public function storeLaboratory(Request $request): RedirectResponse
    {
        $res = $this->gateway->post('/admin/laboratories', $request->all());
        if ($res->successful()) {
            return redirect()->route('admin.labs.index')->with('success', 'Laboratorium berhasil ditambahkan.');
        }
        return back()->withInput()->with('error', $res->json('message') ?? 'Gagal menambahkan lab.');
    }

    public function editLaboratory(int $id): View
    {
        $res = $this->gateway->get('/admin/laboratories');
        $labs = collect($res->json('data') ?? []);
        $lab = $labs->firstWhere('id', $id);
        return view('admin.labs.edit', compact('lab'));
    }

    public function updateLaboratory(Request $request, int $id): RedirectResponse
    {
        $res = $this->gateway->put("/admin/laboratories/{$id}", $request->all());
        if ($res->successful()) {
            return redirect()->route('admin.labs.index')->with('success', 'Laboratorium berhasil diperbarui.');
        }
        return back()->withInput()->with('error', $res->json('message') ?? 'Gagal memperbarui lab.');
    }

    public function destroyLaboratory(int $id): RedirectResponse
    {
        $res = $this->gateway->delete("/admin/laboratories/{$id}");
        return redirect()->route('admin.labs.index')->with('success', 'Laboratorium berhasil dihapus.');
    }

    // ==========================================
    // FACILITIES
    // ==========================================
    public function facilities(Request $request): View
    {
        $res = $this->gateway->get('/admin/facilities', $request->all());
        $facilities = $res->json('data') ?? [];
        $labsRes = $this->gateway->get('/admin/laboratories');
        $laboratories = $labsRes->json('data') ?? [];
        return view('admin.facilities.index', compact('facilities', 'laboratories'));
    }

    public function createFacility(): View
    {
        $labsRes = $this->gateway->get('/admin/laboratories');
        $laboratories = $labsRes->json('data') ?? [];
        return view('admin.facilities.create', compact('laboratories'));
    }

    public function storeFacility(Request $request): RedirectResponse
    {
        $res = $this->gateway->post('/admin/facilities', $request->all());
        if ($res->successful()) {
            return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
        }
        return back()->withInput()->with('error', $res->json('message') ?? 'Gagal menambahkan fasilitas.');
    }

    public function editFacility(int $id): View
    {
        $res = $this->gateway->get('/admin/facilities');
        $facilities = collect($res->json('data') ?? []);
        $facility = $facilities->firstWhere('id', $id);
        $labsRes = $this->gateway->get('/admin/laboratories');
        $laboratories = $labsRes->json('data') ?? [];
        return view('admin.facilities.edit', compact('facility', 'laboratories'));
    }

    public function updateFacility(Request $request, int $id): RedirectResponse
    {
        $res = $this->gateway->put("/admin/facilities/{$id}", $request->all());
        if ($res->successful()) {
            return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
        }
        return back()->withInput()->with('error', $res->json('message') ?? 'Gagal memperbarui fasilitas.');
    }

    public function destroyFacility(int $id): RedirectResponse
    {
        $res = $this->gateway->delete("/admin/facilities/{$id}");
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }

    // ==========================================
    // TECHNICIANS (Pekerja Lapangan, Tanpa Login)
    // ==========================================
    public function technicians(): View
    {
        $res = $this->gateway->get('/admin/technicians');
        $technicians = $res->json('data') ?? [];
        return view('admin.technicians.index', compact('technicians'));
    }

    public function createTechnician(): View
    {
        return view('admin.technicians.create');
    }

    public function storeTechnician(Request $request): RedirectResponse
    {
        $res = $this->gateway->post('/admin/technicians', $request->all());
        if ($res->successful()) {
            return redirect()->route('admin.technicians.index')->with('success', 'Data teknisi berhasil ditambahkan.');
        }
        return back()->withInput()->with('error', $res->json('message') ?? 'Gagal menambahkan teknisi.');
    }

    public function editTechnician(int $id): View
    {
        $res = $this->gateway->get('/admin/technicians');
        $techs = collect($res->json('data') ?? []);
        $technician = $techs->firstWhere('id', $id);
        return view('admin.technicians.edit', compact('technician'));
    }

    public function updateTechnician(Request $request, int $id): RedirectResponse
    {
        $res = $this->gateway->put("/admin/technicians/{$id}", $request->all());
        if ($res->successful()) {
            return redirect()->route('admin.technicians.index')->with('success', 'Data teknisi berhasil diperbarui.');
        }
        return back()->withInput()->with('error', $res->json('message') ?? 'Gagal memperbarui teknisi.');
    }

    public function destroyTechnician(int $id): RedirectResponse
    {
        $res = $this->gateway->delete("/admin/technicians/{$id}");
        return redirect()->route('admin.technicians.index')->with('success', 'Data teknisi berhasil dihapus.');
    }

    // ==========================================
    // STATISTICS
    // ==========================================
    public function statistics(): View
    {
        $res = $this->gateway->get('/admin/dashboard');
        $stats = $res->json('data') ?? [];
        return view('admin.statistics.index', compact('stats'));
    }
}
