<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SaasAttribute;
use App\Models\SaasAttributeValue;
use App\Models\SaasProductVariation;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;

class SaasProductVariations extends Component
{
    public $productId = null;
    public $hasVariations = false;

    #[Validate('required_if:hasVariations,1', message: 'At least one variation is required when variations are enabled')]
    public $variations = [];

    /** @var Collection<int, SaasAttribute> */
    public $productAttributes;
    public $primaryVariationIndex = 0;

    // For edit mode
    public $existingVariations = [];
    public $deletedVariations = [];

    protected $listeners = ['refreshVariations' => '$refresh'];

    protected function rules()
    {
        return [
            'variations.*.attribute_id' => 'required_if:hasVariations,1|exists:saas_attributes,id',
            'variations.*.attribute_value_id' => 'required_if:hasVariations,1|exists:saas_attribute_values,id',
            'variations.*.price' => 'required_if:hasVariations,1|numeric|min:0',
            'variations.*.stock' => 'required_if:hasVariations,1|integer|min:0',
            'variations.*.sku' => 'required_if:hasVariations,1|string'
        ];
    }

    protected function messages()
    {
        return [
            'variations.*.attribute_id.required_if' => 'Please select an attribute for variation #:position',
            'variations.*.attribute_value_id.required_if' => 'Please select a value for variation #:position',
            'variations.*.price.required_if' => 'Please enter a price for variation #:position',
            'variations.*.stock.required_if' => 'Please enter a stock quantity for variation #:position',
            'variations.*.sku.required_if' => 'Please enter a SKU for variation #:position'
        ];
    }

    public function mount($productId = null, $hasVariations = false, $existingVariations = [])
    {
        $this->productId = $productId;
        $this->hasVariations = $hasVariations ? true : false;
        $this->existingVariations = $existingVariations ?? [];
        $this->deletedVariations = [];
        $this->variations = [];

        // Load attributes
        $this->productAttributes = SaasAttribute::with('values')->get();

        // Load existing variations
        if ($this->existingVariations && count($this->existingVariations) > 0) {
            foreach ($this->existingVariations as $index => $variation) {
                $this->variations[] = [
                    'id' => $variation->id,
                    'attribute_id' => $variation->attribute_id,
                    'attribute_value_id' => $variation->attribute_value_id,
                    'price' => $variation->price,
                    'stock' => $variation->stock,
                    'sku' => $variation->sku,
                    'is_primary' => $index === 0 ? true : false
                ];
            }
        }

        // Always add a variation if variations are enabled and none exist
        if ($this->hasVariations && empty($this->variations)) {
            $this->addVariation();
        }

        // Calculate initial stock total
        if ($this->hasVariations) {
            $this->calculateTotalStock();
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Recalculate stock for any property change in variations
        if ($this->hasVariations && strpos($propertyName, 'variations.') !== false) {
            $this->calculateTotalStock();
        }
    }

    public function updatedHasVariations($value)
    {
        $this->hasVariations = $value ? true : false;

        // Add variation if enabled and none exist
        if ($this->hasVariations && empty($this->variations)) {
            $this->addVariation();
        } elseif (!$this->hasVariations) {
            // Mark all existing variations for deletion if disabled
            foreach ($this->variations as $variation) {
                if (isset($variation['id'])) {
                    $this->deletedVariations[] = $variation['id'];
                }
            }

            // Clear the variations array
            $this->variations = [];
        }

        // Recalculate stock only if variations are enabled
        if ($this->hasVariations) {
            $this->calculateTotalStock();
        } else {
            // When switching from variations to no variations, we should not modify the stock field
            // This ensures that when no variations are present, the stock field shows the database value
        }

        // Notify JS of change
        $this->dispatch('hasVariationsChanged', $this->hasVariations);
    }

    public function addVariation()
    {
        $this->variations[] = [
            'attribute_id' => '',
            'attribute_value_id' => '',
            'price' => '',
            'stock' => 0,
            'sku' => '',
            'is_primary' => count($this->variations) === 0 ? true : false
        ];

        if ($this->hasVariations) {
            $this->calculateTotalStock();
        }
    }

    public function removeVariation($index)
    {
        // If it's an existing variation, mark it for deletion
        if (isset($this->variations[$index]['id'])) {
            $this->deletedVariations[] = $this->variations[$index]['id'];
        }

        // Remove from the array
        unset($this->variations[$index]);
        $this->variations = array_values($this->variations);

        // Update primary variation if needed
        $this->updatePrimaryVariationAfterRemoval($index);

        // Recalculate stock only if variations are enabled
        if ($this->hasVariations) {
            $this->calculateTotalStock();
        }
    }

    protected function updatePrimaryVariationAfterRemoval($removedIndex)
    {
        // If the primary variation was removed, set a new one
        if ($removedIndex === $this->primaryVariationIndex && count($this->variations) > 0) {
            $this->setPrimaryVariation(0);
        } elseif ($this->primaryVariationIndex > $removedIndex) {
            // If primary was after the removed index, adjust it
            $this->primaryVariationIndex--;
        }
    }

    public function setPrimaryVariation($index)
    {
        $this->primaryVariationIndex = $index;

        foreach ($this->variations as $i => $variation) {
            $this->variations[$i]['is_primary'] = ($i === $index);
        }
    }

    public function getAttributeValues($index, $attributeId)
    {
        if (!$attributeId) return [];

        $attribute = $this->productAttributes->firstWhere('id', $attributeId);
        return $attribute ? $attribute->values : [];
    }

    public function updatedVariations($value, $key)
    {
        // When attribute_id changes, reset the attribute_value_id
        if (str_contains($key, 'attribute_id')) {
            $index = explode('.', $key)[0];
            $this->variations[$index]['attribute_value_id'] = '';
        }

        // Stock changes should trigger recalculation only if variations are enabled
        if ($this->hasVariations && str_contains($key, 'stock')) {
            $this->calculateTotalStock();
        }
    }

    public function getVariationsToSave()
    {
        return [
            'has_variations' => $this->hasVariations,
            'variations' => $this->variations,
            'deleted_variations' => $this->deletedVariations,
            'primary_variation_index' => $this->primaryVariationIndex,
        ];
    }

    // Calculate and update the total stock from all variations
    protected function calculateTotalStock()
    {
        // Only calculate and update stock when variations are enabled
        if (!$this->hasVariations) {
            return;
        }

        $totalStock = 0;

        if (!empty($this->variations)) {
            foreach ($this->variations as $variation) {
                if (isset($variation['stock']) && is_numeric($variation['stock'])) {
                    $totalStock += (int)$variation['stock'];
                }
            }
        }

        // Update form directly via JavaScript
        $this->js("document.getElementById('stock').value = $totalStock");

        // Also emit events for older scripts
        $this->dispatch('variationsStockUpdated', $totalStock);
        $this->dispatch('update-stock-field', ['totalStock' => $totalStock]);
    }

    public function render()
    {
        return view('livewire.saas-product-variations');
    }
}
