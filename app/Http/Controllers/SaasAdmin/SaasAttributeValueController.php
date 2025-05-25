<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasAttributeValue;
use App\Models\SaasAttribute;
use Illuminate\Http\Request;

class SaasAttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributeValues = SaasAttributeValue::with('attribute')->latest()->paginate(10);
        return view('saas_admin.saas_attribute_value.saas_index', compact('attributeValues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = SaasAttribute::all();
        return view('saas_admin.saas_attribute_value.saas_create', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:saas_attributes,id',
            'value' => 'required|string|max:255',
        ]);

        $attributeValue = new SaasAttributeValue();
        $attributeValue->attribute_id = $request->attribute_id;
        $attributeValue->value = $request->value;
        $attributeValue->save();

        toast('Attribute value created successfully', 'success');
        return redirect()->route('admin.attribute-values.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasAttributeValue $attributeValue)
    {
        $attributeValue->load(['attribute', 'productVariations.product']);
        return view('saas_admin.saas_attribute_value.saas_show', compact('attributeValue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasAttributeValue $attributeValue)
    {
        $attributes = SaasAttribute::all();
        return view('saas_admin.saas_attribute_value.saas_edit', compact('attributeValue', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasAttributeValue $attributeValue)
    {
        $request->validate([
            'attribute_id' => 'required|exists:saas_attributes,id',
            'value' => 'required|string|max:255',
        ]);

        $attributeValue->attribute_id = $request->attribute_id;
        $attributeValue->value = $request->value;
        $attributeValue->save();

        toast('Attribute value updated successfully', 'success');
        return redirect()->route('admin.attribute-values.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasAttributeValue $attributeValue)
    {
        // Check if attribute value is used in product variations
        if ($attributeValue->productVariations()->count() > 0) {
            toast('Cannot delete attribute value because it is used in product variations', 'error');
            return redirect()->route('admin.attribute-values.index');
        }

        $attributeValue->delete();

        toast('Attribute value deleted successfully', 'success');
        return redirect()->route('admin.attribute-values.index');
    }
}
