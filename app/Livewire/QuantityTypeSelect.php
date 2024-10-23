<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\QuantityType;

class QuantityTypeSelect extends Component
{
    public $selectedQuantityType = null;
    public $quantityTypes = [];
    public $newQuantityTypeName = '';

    protected $listeners = ['refreshQuantityTypes' => 'loadQuantityTypes'];

    public function mount($selected = null)
    {
        $this->selectedQuantityType = $selected;
        $this->loadQuantityTypes();
    }

    public function loadQuantityTypes()
    {
        $this->quantityTypes = QuantityType::orderBy('name')->get();
    }

    public function addQuantityType()
    {
        $this->validate([
            'newQuantityTypeName' => 'required|string|unique:quantity_types,name',
        ]);

        $quantityType = QuantityType::create(['name' => $this->newQuantityTypeName]);

        $this->newQuantityTypeName = '';
        $this->loadQuantityTypes();
        $this->selectedQuantityType = $quantityType->id;

        session()->flash('message', 'Тип количества добавлен.');
    }

    public function deleteQuantityType($id)
    {
        $quantityType = QuantityType::find($id);

        if ($quantityType) {
            if ($quantityType->products()->exists()) {
                session()->flash('error', 'Невозможно удалить тип количества, связанный с продуктами.');
                return;
            }

            $quantityType->delete();
            $this->loadQuantityTypes();
            session()->flash('message', 'Тип количества удален.');

            if ($this->selectedQuantityType == $id) {
                $this->selectedQuantityType = null;
            }
        }
    }

    public function updatedSelectedQuantityType($value)
    {
        $this->emitUp('quantityTypeSelected', $value);
    }


    public function render()
    {
        return view('livewire.quantity-type-select');
    }
}
