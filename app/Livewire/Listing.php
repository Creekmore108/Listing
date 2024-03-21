<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Listing as ModelListing;


class Listing extends Component
{
    public $showModal = false;
    public $editMode = false;
    public $name;
    public $address;
    public $website;
    public $email;
    public $phone;
    public $bio;
    public $listingId;
    public $search = '';

    protected $rules = [
        'name' => 'required',
        'address' => 'required',
        'website' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'bio' => 'required',
    ];

   public function showCreateModal()
   {
    $this->showModal = true;
   }

   public function showEditModal($id)
   {
        $this->showModal = true;
        $this->editMode = true;
        $this->listingId = $id;

        $listing = ModelListing::find($id);
        $this->name = $listing->name;
        $this->address = $listing->address;
        $this->website = $listing->website;
        $this->email = $listing->email;
        $this->phone = $listing->phone;
        $this->bio = $listing->bio;
   }

   public function createListing()
   {
    $this->validate();

        Auth::user()->listings()->create([
            'name' => $this->name,
            'address' => $this->address,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'bio' => $this->bio
        ]);
        session()->flash('flash.banner', 'Listing created successfuly');
        $this->reset();
   }

    public function render()
    {
        // dd(Auth::user()->listings);
        return view('livewire.listing',[
            'listings' => Auth::user()->listings
        ]);
    }
}
