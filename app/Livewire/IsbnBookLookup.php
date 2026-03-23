<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GoogleBooksService;
use App\DTO\BookData;

class IsbnBookLookup extends Component
{
    public string $isbn='';
    public ?array $book = null;
    public ?string $error = null;

    // Validation rules
    public function rules()
    {
        return [
            'isbn' =>[
                'required', function($attribute, $value, $fail){
                    $normalised_isbn = str_replace(['-',' '], '', $value);
                    /* Just checking the lenght of the isbn
                    * Must be between 9 (old format) and 13 (new format)
                    */
                    if(strlen($normalised_isbn) < 9 || strlen($normalised_isbn) >13){
                        $fail('The '.$attribute. ' must be between 9 and 13 characters.');
                    }

                    /* Future improvements:
                    *  add a regex to check the format
                    */
                }
            ]
        ];
    }

    // Clear reasult card
    public function clearResults()
    {
        $this->book = [];
        $this->isbn = '';

        $this->clearErrors();
    }

    // Clear validation errors
    public function clearErrors()
    {
        $this->error = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Form submit
    public function lookup(GoogleBooksService $service)
    {
        $this->reset(['book']);
        $this->clearErrors();

        $this->validate();

        $result = $service->searchByIsbn($this->isbn);

        if(!$result->isSuccess()){
            $this->error = $result->message;
            return;
        }

        $this->book=$result->data ? $result->data->toArray() : null;

    }

    // Render
    public function render()
    {
        return view('livewire.isbn-book-lookup')->layout('layouts.layout');
    }
}

