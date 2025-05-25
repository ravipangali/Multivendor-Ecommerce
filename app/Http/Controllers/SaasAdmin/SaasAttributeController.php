<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasAttribute;
use Illuminate\Http\Request;

class SaasAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = SaasAttribute::with('values')->latest()->paginate(10);
        return view('saas_admin.saas_attribute.saas_index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saas_admin.saas_attribute.saas_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:saas_attributes',
        ]);

        $attribute = new SaasAttribute();
        $attribute->name = $request->name;
        $attribute->save();

        toast('Attribute created successfully', 'success');

        return redirect()->route('admin.attributes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasAttribute $attribute)
    {
        $attribute->load('values');
        return view('saas_admin.saas_attribute.saas_show', compact('attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasAttribute $attribute)
    {
        return view('saas_admin.saas_attribute.saas_edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasAttribute $attribute)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:saas_attributes,name,' . $attribute->id,
        ]);

        $attribute->name = $request->name;
        $attribute->save();

        toast('Attribute updated successfully', 'success');

        return redirect()->route('admin.attributes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasAttribute $attribute)
    {
        // Check if attribute has values or is used in products before deleting
        if ($attribute->values()->count() > 0 || $attribute->productVariations()->count() > 0) {
            toast('Cannot delete attribute because it has associated values or is used in products', 'error');
            return redirect()->route('admin.attributes.index');
        }

        $attribute->delete();

        toast('Attribute deleted successfully', 'success');

        return redirect()->route('admin.attributes.index');
    }
}
