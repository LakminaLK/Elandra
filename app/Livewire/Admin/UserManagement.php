<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filterRole = '';
    public $filterStatus = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // User form fields
    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'customer';
    public $phone = '';
    public $is_active = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'filterRole' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255'],
            'role' => 'required|in:customer,admin',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ];

        if ($this->userId) {
            // Update rules
            $rules['email'][] = Rule::unique('users', 'email')->ignore($this->userId);
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            // Create rules
            $rules['email'][] = 'unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->phone = $user->phone ?? '';
        $this->is_active = $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showEditModal = true;
    }

    public function openDeleteModal($userId)
    {
        $this->userId = $userId;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'customer';
        $this->phone = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createUser()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'email_verified_at' => now(),
        ];

        User::create($userData);

        session()->flash('message', 'User created successfully!');
        $this->closeModal();
    }

    public function updateUser()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);
        
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        session()->flash('message', 'User updated successfully!');
        $this->closeModal();
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->userId);
        
        if ($user->orders()->exists()) {
            session()->flash('error', 'Cannot delete user with existing orders. Deactivate instead.');
        } else {
            $user->delete();
            session()->flash('message', 'User deleted successfully!');
        }
        
        $this->closeModal();
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "User {$status} successfully!");
    }

    public function render()
    {
        $query = User::query()
            ->withCount(['orders', 'cartItems']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === '1');
        }

        $users = $query->orderBy($this->sortField, $this->sortDirection)
                      ->paginate(15);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}