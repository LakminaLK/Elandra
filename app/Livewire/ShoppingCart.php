<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;

class ShoppingCart extends Component
{
    public $cartItems = [];
    public $selectedItems = [];
    public $selectAll = false;
    public $isLoading = false;
    public $couponCode = '';
    public $discount = 0;
    public $showCouponForm = false;
    public $selectionVersion = 0; // Force reactivity
    #[Computed]
    public function hasSelectedItems()
    {
        return count($this->selectedItems ?? []) > 0;
    }
    
    #[Computed]
    public function selectedCount()
    {
        return count($this->selectedItems ?? []);
    }

    public function mount()
    {
        $this->refreshCart();
        // Ensure selected items is always an array
        if (!is_array($this->selectedItems)) {
            $this->selectedItems = [];
        }
    }
    
    public function hydrate()
    {
        // Called after Livewire hydrates the component - ensure proper state
        if (!is_array($this->selectedItems)) {
            $this->selectedItems = [];
        }
    }

    public function refreshCart()
    {
        if (auth()->check()) {
            $this->cartItems = Cart::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();
                
            // Update select all state after refresh
            $this->updateSelectAllState();
        } else {
            $this->cartItems = collect();
            $this->selectedItems = [];
            $this->selectAll = false;
        }
    }

    public function forceRefresh()
    {
        $this->refreshCart();
        $this->dispatch('cart-refreshed');
    }

    #[On('refresh-cart')]
    public function handleRefreshCart()
    {
        $this->refreshCart();
    }

    public function incrementQuantity($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === auth()->id()) {
            $this->updateQuantity($cartItemId, $cartItem->quantity + 1);
        }
    }

    public function decrementQuantity($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === auth()->id()) {
            $this->updateQuantity($cartItemId, $cartItem->quantity - 1);
        }
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please log in to update cart.');
            return;
        }

        try {
            $cartItem = Cart::findOrFail($cartItemId);

            // Check if cart item belongs to user
            if ($cartItem->user_id !== auth()->id()) {
                session()->flash('error', 'Unauthorized action');
                return;
            }

            if ($quantity <= 0) {
                $this->removeItem($cartItemId);
                return;
            }

            // Get product from MongoDB to check stock
            $product = $cartItem->product;
            if (!$product) {
                session()->flash('error', 'Product no longer available');
                return;
            }

            // Check maximum quantity limits: 3 items OR stock quantity (whichever is lower)
            $maxAllowed = 3;
            if (isset($product->stock_quantity)) {
                $maxAllowed = min(3, $product->stock_quantity);
            }

            if ($quantity > $maxAllowed) {
                if ($maxAllowed < 3) {
                    session()->flash('error', "Only {$maxAllowed} items available in stock");
                } else {
                    session()->flash('error', "Maximum 3 items per product allowed");
                }
                return;
            }

            // Check if enough stock is available
            if (isset($product->stock_quantity) && $product->stock_quantity < $quantity) {
                session()->flash('error', "Insufficient stock. Available: {$product->stock_quantity}");
                return;
            }

            // Update the quantity directly in database
            $cartItem->update(['quantity' => $quantity]);
            
            // Refresh the component's data immediately
            $this->mount();
            
            // Dispatch multiple events for cart counter updates
            $this->dispatch('cart-updated');
            $this->dispatch('refreshCartCount');
            
            session()->flash('success', 'Cart updated successfully');

        } catch (\Exception $e) {
            \Log::error('Update quantity error: ' . $e->getMessage());
            session()->flash('error', 'Failed to update cart');
        }
    }

    public function removeItem($cartItemId)
    {
        if (!auth()->check()) {
            return;
        }

        $this->isLoading = true;

        try {
            $cartItem = Cart::findOrFail($cartItemId);

            // Check if cart item belongs to user
            if ($cartItem->user_id !== auth()->id()) {
                session()->flash('error', 'Unauthorized action');
                $this->isLoading = false;
                return;
            }

            $productName = $cartItem->product_name;
            $cartItem->delete();
            
            // Remove from selected items if it was selected
            $this->selectedItems = array_values(array_diff($this->selectedItems, [$cartItemId]));
            
            // Refresh cart immediately
            $this->refreshCart();
            
            // Dispatch events for other components
            $this->dispatch('cart-updated');
            $this->dispatch('refreshCartCount');
            $this->dispatch('item-removed', ['itemId' => $cartItemId, 'productName' => $productName]);
            
            session()->flash('success', "{$productName} removed from cart");

        } catch (\Exception $e) {
            \Log::error('Remove item error: ' . $e->getMessage());
            session()->flash('error', 'Failed to remove item');
        }

        $this->isLoading = false;
    }

    public function clearCart()
    {
        if (!auth()->check()) {
            return;
        }

        $this->isLoading = true;

        try {
            Cart::where('user_id', auth()->id())->delete();
            $this->refreshCart();
            
            session()->flash('success', 'Cart cleared successfully');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to clear cart');
        }

        $this->isLoading = false;
    }

    public function getTotalAmountProperty()
    {
        return $this->cartItems->sum('total_price');
    }

    public function getTotalItemsProperty()
    {
        return $this->cartItems->sum('quantity');
    }
    
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Select all items
            $this->selectedItems = $this->cartItems->pluck('id')->toArray();
        } else {
            // Deselect all items
            $this->selectedItems = [];
        }
        
        // Force refresh to ensure visual state updates
        $this->dispatch('selection-updated');
    }
    
    public function updatedSelectedItems()
    {
        // This Livewire hook runs whenever selectedItems property is updated
        // Ensure all items in selectedItems are strings for consistency
        $this->selectedItems = array_map('strval', $this->selectedItems ?? []);
        
        $count = count($this->selectedItems);
        $hasSelected = $count > 0;
        
        $logMessage = "updatedSelectedItems hook triggered - Count: " . $count . ", HasSelected: " . ($hasSelected ? 'true' : 'false');
        \Log::info($logMessage);
        
        // Dispatch event for JavaScript console logging
        $this->dispatch('selection-updated', [
            'count' => $count,
            'hasSelected' => $hasSelected,
            'items' => $this->selectedItems,
            'message' => $logMessage
        ]);
        
        $this->selectionVersion++; // Force reactivity
        $this->updateSelectAllState();
    }
    
    public function updateSelectAllState()
    {
        // Ensure selected items is always an array
        if (!is_array($this->selectedItems)) {
            $this->selectedItems = [];
        }
        
        // Clean up selectedItems - remove any items that no longer exist
        $validItemIds = $this->cartItems->pluck('id')->map(function($id) {
            return (string) $id; // Keep as string for wire:model compatibility
        })->toArray();
        
        // Convert selected items to strings and filter valid ones
        $this->selectedItems = array_map('strval', $this->selectedItems);
        $this->selectedItems = array_intersect($this->selectedItems, $validItemIds);
        $this->selectedItems = array_values($this->selectedItems); // Re-index array
        
        // Update select all state
        $totalItems = $this->cartItems->count();
        $selectedCount = count($this->selectedItems);
        
        // Set selectAll to true only if all items are selected and we have items
        $previousSelectAll = $this->selectAll;
        $this->selectAll = ($selectedCount === $totalItems && $totalItems > 0);
        
        // Log for debugging
        \Log::info("updateSelectAllState: Total={$totalItems}, Selected={$selectedCount}, SelectAll changed from {$previousSelectAll} to {$this->selectAll}");
    }
    
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            // If currently checked, uncheck all items
            $this->selectedItems = [];
            $this->selectAll = false;
        } else {
            // If currently unchecked, select all items
            // Ensure all IDs are stored as strings to match wire:model values
            $this->selectedItems = $this->cartItems->pluck('id')->map(function($id) {
                return (string) $id; // Convert to string for wire:model compatibility
            })->toArray();
            $this->selectAll = true;
        }
        
        // Single reactivity update
        $this->selectionVersion++;
        
        // Log for debugging
        \Log::info("toggleSelectAll: SelectAll={$this->selectAll}, SelectedItems=" . json_encode($this->selectedItems));
    }
    
    public function toggleItem($itemId)
    {
        // Convert to integer for consistent comparison
        $itemId = (int) $itemId;
        
        if (in_array($itemId, $this->selectedItems)) {
            // Remove item from selection
            $this->selectedItems = array_values(array_diff($this->selectedItems, [$itemId]));
        } else {
            // Add item to selection
            $this->selectedItems[] = $itemId;
        }
        
        // Manually trigger the updatedSelectedItems hook since we're using wire:click instead of wire:model
        $this->updatedSelectedItems();
        
        // Force Livewire to re-render the component
        $this->dispatch('selection-updated');
        
        // Log for debugging
        \Log::info("toggleItem: ItemId={$itemId}, SelectedItems=" . json_encode($this->selectedItems) . ", Count=" . count($this->selectedItems));
    }
    
    #[Computed]
    public function selectedTotal()
    {
        // Convert selectedItems to integers for database comparison
        $selectedIds = array_map('intval', $this->selectedItems ?? []);
        $selectedItems = $this->cartItems->whereIn('id', $selectedIds);
        return $selectedItems->sum('total_price');
    }
    
    #[Computed]
    public function selectedItemsCount()
    {
        return count($this->selectedItems);
    }
    
    public function refreshComponent()
    {
        // Force component refresh - useful for resolving reactivity issues
        $this->updateSelectAllState();
        $this->dispatch('selection-updated');
    }
    
    public function clearSelection()
    {
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->selectionVersion++;
        $this->updatedSelectedItems();
    }
    
    #[Computed]
    public function subtotal()
    {
        return $this->cartItems->sum('total_price');
    }

    #[Computed]
    public function tax()
    {
        return $this->subtotal * 0.10; // 10% tax rate
    }

    #[Computed]
    public function shipping()
    {
        return $this->subtotal > 100 ? 0 : 10.00; // Free shipping over $100
    }

    #[Computed]
    public function total()
    {
        return ($this->subtotal - $this->discount) + $this->tax + $this->shipping;
    }
    
    #[Computed]
    public function selectedShipping()
    {
        return $this->selectedTotal > 100 ? 0 : 10.00; // Free shipping over $100
    }
    
    #[Computed]
    public function selectedTax()
    {
        return $this->selectedTotal * 0.10; // 10% tax rate
    }
    
    #[Computed]
    public function selectedFinalTotal()
    {
        return $this->selectedTotal + $this->selectedShipping + $this->selectedTax;
    }

    public function proceedToCheckout()
    {
        try {
            \Log::info('Proceed to checkout called');
            
            // Check if any items are selected
            if (empty($this->selectedItems)) {
                session()->flash('error', 'Please select items to checkout');
                return;
            }
            
            $selectedCartItems = $this->cartItems->whereIn('id', $this->selectedItems);
            
            if ($selectedCartItems->isEmpty()) {
                session()->flash('error', 'Selected items not found in cart');
                return;
            }

            \Log::info('Selected items for checkout: ' . $selectedCartItems->count());

            // Check stock availability for selected items
            foreach ($selectedCartItems as $item) {
                try {
                    $product = $item->product;
                    if ($product && isset($product->stock_quantity) && $product->stock_quantity < $item->quantity) {
                        session()->flash('error', "Sorry, {$product->name} has only {$product->stock_quantity} items in stock");
                        return;
                    }
                } catch (\Exception $e) {
                    // If product doesn't exist or there's an error, continue to checkout
                    \Log::warning('Product access error in cart: ' . $e->getMessage());
                }
            }

            \Log::info('Redirecting to checkout with selected items: ' . implode(',', $this->selectedItems));
            // Store selected items in session for checkout
            session(['selected_cart_items' => $this->selectedItems]);
            // Redirect to checkout page
            return redirect()->route('checkout');
            
        } catch (\Exception $e) {
            \Log::error('Error in proceedToCheckout: ' . $e->getMessage());
            session()->flash('error', 'An error occurred. Please try again.');
        }
    }

    public function testButton()
    {
        session()->flash('message', 'Button click works! Cart has ' . $this->cartItems->count() . ' items.');
    }
    
    #[On('cart-updated')]
    public function handleCartUpdated()
    {
        $this->refreshCart();
    }

    #[On('refreshCartCount')]
    public function handleRefreshCartCount()
    {
        $this->refreshCart();
    }

    #[On('add-to-cart')]
    public function handleAddToCart($productId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $product = Product::find($productId);
        if (!$product) return;
        
        $existingItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();
            
        if ($existingItem) {
            $existingItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'quantity' => 1,
                'price' => $product->price
            ]);
        }
        
        $this->refreshCart();
        $this->dispatch('cart-updated');
    }
    
    public function applyCoupon()
    {
        // Simple coupon system - enhance as needed
        $validCoupons = [
            'SAVE10' => 10,
            'WELCOME20' => 20,
            'STUDENT15' => 15
        ];
        
        if (array_key_exists($this->couponCode, $validCoupons)) {
            $this->discount = $this->subtotal * ($validCoupons[$this->couponCode] / 100);
            $this->showCouponForm = false;
            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => "Coupon applied! You saved {$validCoupons[$this->couponCode]}%"
            ]);
        } else {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Invalid coupon code'
            ]);
        }
    }
    
    public function removeCoupon()
    {
        $this->discount = 0;
        $this->couponCode = '';
    }

    public function getFormattedTotalProperty()
    {
        return '$' . number_format($this->totalAmount, 2);
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
