<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MongoProduct;

class ProductSearch extends Component
{
    public $search = '';
    public $results = [];
    public $showResults = false;

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->results = Product::with('category')
                ->active()
                ->search($this->search)
                ->take(8)
                ->get();
            $this->showResults = true;
        } else {
            $this->results = [];
            $this->showResults = false;
        }
    }

    public function selectProduct($productId)
    {
        $product = Product::findOrFail($productId);
        return redirect()->route('products.show', $product->slug);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->results = [];
        $this->showResults = false;
    }

    public function render()
    {
        return view('livewire.product-search');
    }
}
