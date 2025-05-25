<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasUnit;
use Illuminate\Http\Request;

class SaasUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = SaasUnit::latest()->paginate(10);
        return view('saas_admin.saas_unit.saas_index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saas_admin.saas_unit.saas_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:saas_units',
        ]);

        $unit = new SaasUnit();
        $unit->name = $request->name;
        $unit->save();

        toast('Unit created successfully', 'success');
        return redirect()->route('admin.units.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasUnit $unit)
    {
        return view('saas_admin.saas_unit.saas_show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasUnit $unit)
    {
        return view('saas_admin.saas_unit.saas_edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasUnit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:saas_units,name,' . $unit->id,
        ]);

        $unit->name = $request->name;
        $unit->save();

        toast('Unit updated successfully', 'success');
        return redirect()->route('admin.units.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasUnit $unit)
    {
        // Check if unit is used in any products before deleting
        if ($unit->products()->count() > 0) {
            toast('Cannot delete unit because it is used in products', 'error');
            return redirect()->route('admin.units.index');
        }

        $unit->delete();

        toast('Unit deleted successfully', 'success');
        return redirect()->route('admin.units.index');
    }
}
