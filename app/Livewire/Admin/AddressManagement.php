<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Address;
use Livewire\Component;
use Livewire\WithPagination;

class AddressManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateModal = false;
    public $selectedUserId = null;
    
    // Address form fields
    public $addressId = null;
    public $user_id = '';
    public $type = 'shipping';
    public $street = '';
    public $city = '';
    public $state = '';
    public $postal_code = '';
    public $country = 'USA';
    public $is_default = false;

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:shipping,billing',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'is_default' => 'boolean',
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->addressId = null;
        $this->user_id = '';
        $this->type = 'shipping';
        $this->street = '';
        $this->city = '';
        $this->state = '';
        $this->postal_code = '';
        $this->country = 'USA';
        $this->is_default = false;
        $this->resetValidation();
    }

    public function createAddress()
    {
        $this->validate();

        // If setting as default, remove default from other addresses of same type for this user
        if ($this->is_default) {
            Address::where('user_id', $this->user_id)
                   ->where('type', $this->type)
                   ->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => $this->user_id,
            'type' => $this->type,
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'is_default' => $this->is_default,
        ]);

        session()->flash('message', 'Address created successfully!');
        $this->closeModal();
    }

    public function render()
    {
        $addresses = Address::with('user')
                           ->when($this->search, function($query) {
                               $query->where(function($q) {
                                   $q->where('street', 'like', '%' . $this->search . '%')
                                     ->orWhere('city', 'like', '%' . $this->search . '%')
                                     ->orWhere('state', 'like', '%' . $this->search . '%')
                                     ->orWhereHas('user', function($userQuery) {
                                         $userQuery->where('name', 'like', '%' . $this->search . '%');
                                     });
                               });
                           })
                           ->latest()
                           ->paginate(15);

        $users = User::select('id', 'name')->get();

        return view('livewire.admin.address-management', [
            'addresses' => $addresses,
            'users' => $users,
        ]);
    }
}