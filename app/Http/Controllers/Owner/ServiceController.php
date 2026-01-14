<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\InventoryItem;
use App\Models\ServiceMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('tenant_id', Auth::user()->tenant_id)->with('materials.inventoryItem')->paginate(10);
        $inventoryItems = InventoryItem::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('owner.services.index', compact('services', 'inventoryItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'unit' => 'required', // kg, pc
        ]);

        $data['tenant_id'] = Auth::user()->tenant_id;

        DB::transaction(function () use ($data, $request) {
            $service = Service::create($data);

            if ($request->has('materials')) {
                foreach ($request->materials as $mat) {
                    if (!empty($mat['inventory_item_id']) && !empty($mat['quantity'])) {
                        ServiceMaterial::create([
                            'service_id' => $service->id,
                            'inventory_item_id' => $mat['inventory_item_id'],
                            'quantity' => $mat['quantity']
                        ]);
                    }
                }
            }
        });

        return back()->with('success', 'Service created successfully.');
    }

    public function update(Request $request, Service $service)
    {
        if ($service->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'unit' => 'required',
        ]);

        DB::transaction(function () use ($data, $request, $service) {
            $service->update($data);

            if ($request->has('materials')) {
                // Remove existing materials
                ServiceMaterial::where('service_id', $service->id)->delete();

                // Add new materials
                foreach ($request->materials as $mat) {
                    if (!empty($mat['inventory_item_id']) && !empty($mat['quantity'])) {
                        ServiceMaterial::create([
                            'service_id' => $service->id,
                            'inventory_item_id' => $mat['inventory_item_id'],
                            'quantity' => $mat['quantity']
                        ]);
                    }
                }
            }
        });

        return back()->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        // ServiceMaterial will cascade if FK is set, generally safer to let DB handle or soft deletes.
        // Assuming cascade on DB level or manual cleanup if needed.
        // For now, strict delete.
        $service->delete();

        return back()->with('success', 'Service deleted successfully.');
    }
}
