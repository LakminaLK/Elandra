<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
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
    
    // Real-time polling for cart updates
    protected $polling = true;

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        if (auth()->check()) {
            $this->cartItems = Cart::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->cartItems = collect();
        }
    }

    public function updateQuantity($cartItemId, $quantity)
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

            if ($quantity <= 0) {
                $this->removeItem($cartItemId);
                return;
            }

            // Check stock availability
            $product = $cartItem->product; // Uses getProductAttribute() method
            if ($product && $product->stock_quantity < $quantity) {
                session()->flash('error', 'Insufficient stock. Available: ' . $product->stock_quantity);
                $this->isLoading = false;
                return;
            }

            $cartItem->updateQuantity($quantity);
            $this->refreshCart();
            
            session()->flash('success', 'Cart updated successfully');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update cart');
        }

        $this->isLoading = false;
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

            $cartItem->delete();
            $this->refreshCart();
            
            // Real-time event broadcasting
            $this->dispatch('cart-updated');
            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => 'Item removed from cart'
            ]);
            session()->flash('success', 'Item removed from cart');

        } catch (\Exception $e) {
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
    
    public function toggleItemSelection($cartItemId)
    {
        if (in_array($cartItemId, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$cartItemId]);
        } else {
            $this->selectedItems[] = $cartItemId;
        }
        
        // Update select all checkbox state
        $this->selectAll = count($this->selectedItems) === $this->cartItems->count();
    }
    
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedItems = $this->cartItems->pluck('id')->toArray();
        } else {
            $this->selectedItems = [];
        }
    }
    
    public function getSelectedTotalProperty()
    {
        return $this->cartItems->whereIn('id', $this->selectedItems)->sum('total_price');
    }
    
    public function getSelectedItemsCountProperty()
    {
        return count($this->selectedItems);
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

    public function getSubtotalProperty()
    {
        return $this->cartItems->sum('total_price');
    }

    public function getTaxProperty()
    {
        return $this->subtotal * 0.08; // 8% tax rate
    }

    public function getShippingProperty()
    {
        return $this->subtotal > 100 ? 0 : 10.00; // Free shipping over $100
    }

    public function getTotalProperty()
    {
        return ($this->subtotal - $this->discount) + $this->tax + $this->shipping;
    }
    
    // Selected items shipping calculation
    public function getSelectedShippingProperty()
    {
        return $this->selectedTotal > 100 ? 0 : 10.00; // Free shipping over $100
    }
    
    // Selected items tax calculation
    public function getSelectedTaxProperty()
    {
        return $this->selectedTotal * 0.08; // 8% tax rate
    }
    
    // Selected items final total
    public function getSelectedFinalTotalProperty()
    {
        return $this->selectedTotal + $this->selectedShipping + $this->selectedTax;
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
