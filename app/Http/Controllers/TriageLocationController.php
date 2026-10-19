<?php

namespace App\Http\Controllers;

use App\Models\TriageLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TriageLocationController extends Controller
{
    /**
     * Render the main Blade index view and pass database locations.
     */
    public function index(): View
    {
        // Fetches all locations so the map counter and markers load on page refresh.
        // If your database uses status checks, you can use: TriageLocation::where('status', 'Aktif')->latest()->get();
        $locations = TriageLocation::latest()->get();

        return view('components.Triage.index', compact('locations'));
    }

    /**
     * API endpoint to get all active locations as JSON for Leaflet Map or Alpine fetch.
     */
    public function getLocations(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => TriageLocation::where('status', 'Aktif')->latest()->get()
        ]);
    }

    /**
     * Store a newly created triage location in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'nama_lokasi'   => 'required|string|max:255',
            'kategori'      => 'nullable|string|max:100',
            'alamat'        => 'nullable|string',
            'province_id'   => 'nullable|integer',
            'regency_id'    => 'nullable|integer',
            'district_id'   => 'nullable|integer',
            'village_id'    => 'nullable|integer',
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'tier'          => 'nullable|string|max:50',
        ]);

        $location = TriageLocation::create([
            'nama_lokasi'   => $validated['nama_lokasi'],
            'kategori'      => $validated['kategori'] ?? 'Rumah_sakit',
            'alamat'        => $validated['alamat'] ?? null,
            'province_id'   => $validated['province_id'] ?? null,
            'regency_id'    => $validated['regency_id'] ?? null,
            'district_id'   => $validated['district_id'] ?? null,
            'village_id'    => $validated['village_id'] ?? null,
            'latitude'      => $validated['latitude'],
            'longitude'     => $validated['longitude'],
            'tier'          => $validated['tier'] ?? null,
            'status'        => 'Aktif',
        ]);

        if ($request->wantsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lokasi triage berhasil ditambahkan!',
                'data'    => $location,
            ], 201);
        }

        return redirect()->back()->with('success', 'Lokasi triage berhasil ditambahkan!');
    }

    /**
     * Update the specified triage location.
     */
    public function update(Request $request, $id): JsonResponse|RedirectResponse
    {
        $triageLocation = TriageLocation::findOrFail($id);

        $validated = $request->validate([
            'nama_lokasi'   => 'required|string|max:255',
            'kategori'      => 'nullable|string|max:100',
            'alamat'        => 'nullable|string',
            'province_id'   => 'nullable|integer',
            'regency_id'    => 'nullable|integer',
            'district_id'   => 'nullable|integer',
            'village_id'    => 'nullable|integer',
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'tier'          => 'nullable|string|max:50',
        ]);

        $triageLocation->update($validated);

        if ($request->wantsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lokasi triage berhasil diperbarui!',
                'data'    => $triageLocation,
            ]);
        }

        return redirect()->back()->with('success', 'Lokasi triage berhasil diperbarui!');
    }

    /**
     * Remove the specified triage location from storage.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        $triageLocation = TriageLocation::findOrFail($id);
        $triageLocation->delete();

        if ($request->wantsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil dihapus!'
            ]);
        }

        return redirect()->back()->with('success', 'Lokasi berhasil dihapus!');
    }
}