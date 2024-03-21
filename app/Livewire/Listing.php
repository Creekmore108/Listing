<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Listing as ModelListing;
use Livewire\WithPagination;


class Listing extends Component
{
    use WithPagination;

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
        'website' => 'required|url',
        'email' => 'required|email',
        'phone' => 'required',
        'bio' => 'required',
    ];

   public function showCreateModal()
   {
    $this->showModal = true;
   }

   public function updatedShowModal()
    {
        $this->editMode = false;
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

        session()->flash('message', '');
        session()->flash('flash.banner', 'Listing created successfuly');
        session()->flash('flash.bannerStyle','success');
        $this->reset();
   }

   function listingUpdate()
   {
    $this->validate();

    $listing = ModelListing::find($this->listingId);

    $listing->update([
         'name' => $this->name,
        'address' => $this->address,
        'website' => $this->website,
        'email' => $this->email,
        'phone' => $this->phone,
        'bio' => $this->bio
    ]);
    session()->flash('message',"");
    session()->flash('flash.banner', 'Listing updated successfuly');
    session()->flash('flash.bannerStyle','success');
    $this->reset();

   }

   public function deleteListing($id)
   {
    $listing = ModelListing::findOrFail($id);
    $listing->delete();
    session()->flash('message',"");
    session()->flash('flash.banner', 'Listing deleted successfuly');
    session()->flash('flash.bannerStyle','success');
   }

    public function render()
    {
        $listings = Auth::user()->listings()->paginate(3);
        if ($this->search) {
            $listings = Auth::user()
            ->listings()
            ->where('name', 'like', "%{$this->search}%")
            ->paginate(2);
        }
        return view('livewire.listing',[
            'listings' => $listings
        ]);
    }
}
