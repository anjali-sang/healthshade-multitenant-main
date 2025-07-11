<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SubscriptionComponent extends Component
{
    public $subscriptionId = '';
    public $name = '';
    public $price = '';
    public $max_users = '';
    public $max_locations = '';
    public $description = '';
    public $is_active = '';
    public $duration = '3';

    public function createSubscription()
    {
        $this->validate([
            'name' => 'required|min:3|max:25|unique:subscriptions,name',
            'price' => 'required|numeric|min:0',
            'max_users' => 'required|numeric|min:1',
            'max_locations' => 'required|numeric|min:1',
            'description' => 'required|min:3|max:250',
            'duration' => 'required',
        ]);
        // Handle checkbox 'is_active' as a boolean value
        if ($this->is_active == 'true') {
            $is_active = true;
        } else {
            $is_active = false;
        }
        $subscription = Subscription::create([
            'name' => $this->name,
            'price' => $this->price,
            'max_users' => $this->max_users,
            'description' => $this->description,
            'max_locations' => $this->max_locations,
            'is_active' => $is_active,
            'duration' => $this->duration,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ]);
        $this->dispatch('pg:eventRefresh-subscriptions-list-mlwfa0-table');
        $this->dispatch('close-modal', 'add-subscription-modal');
    }

    #[On('edit')]
    public function startEdit($rowId)
    {
        $this->editing = true;
        $this->subscriptionId = $rowId;

        $subscription = Subscription::findOrFail($rowId);
        $this->name = $subscription->name;
        $this->price = $subscription->price;
        $this->max_users = $subscription->max_users;
        $this->max_locations = $subscription->max_locations;
        $this->description = $subscription->description;
        $this->is_active = $subscription->is_active;
        $this->dispatch('open-modal', 'edit-subscription-modal');

    }

    public function updateSubscription()
    {

        $this->price = str_replace(',', '', $this->price);
        $this->price = (float) $this->price;
        $this->validate([
            'name' => 'required|min:3|max:25',
            'price' => 'required|numeric|min:0',
            'max_users' => 'required|numeric|min:1',
            'max_locations' => 'required|numeric|min:1',
            'description' => 'required|min:3|max:250',
        ]);
        if (
            Subscription::where('name', $this->name)
                ->where('is_active', true)
                ->where('id', '!=', $this->subscriptionId)
                ->exists()
        ) {
            $this->addError('name', 'The name must be unique.');
            return;
        }

        $subscription = Subscription::findOrFail($this->subscriptionId);
        $subscription->update([
            'name' => $this->name,
            'price' => $this->price,
            'max_users' => $this->max_users,
            'max_locations' => $this->max_locations,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'updated_by' => auth()->user()->id
        ]);

        $this->dispatch('subscription-updated');
        $this->dispatch('pg:eventRefresh-subscriptions-list-mlwfa0-table');
        $this->reset();
        $this->dispatch('close-modal', 'edit-subscription-modal');
    }


    public function render()
    {
        // Fetching subscriptions from the database
        $subscriptions = Subscription::all();

        // Rendering the view and passing the subscriptions data
        return view('livewire.admin.subscription-component', compact('subscriptions'));
    }
}
