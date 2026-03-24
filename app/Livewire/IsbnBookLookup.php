<?php

namespace App\Livewire;

use Livewire\Component;
use App\Interfaces\BookServiceInterface;
use App\DTO\BookData;

class IsbnBookLookup extends Component
{
    public string $isbn='';
    public ?array $book = null;
    public ?string $error = null;

    protected const ISBN_REGEX = '/^(?:ISBN(?:-1[03])?:? )?(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$/';
    protected const ISBN_REGEX_PREFIX = '/^\s*ISBN(-10|-13)?\s*:?\s*/';

    // Validation rules
    public function rules()
    {
        return [
            'isbn' =>[
                'required', function($attribute, $value, $fail){

                    // check ISBN format with regex
                    if(!preg_match(self::ISBN_REGEX, trim($value))){
                        $fail('The '.$attribute. ' must be a valid format.');
                    }else{
                        // if ISBN match regex, remove prefix if any
                        $normalised_isbn = preg_replace(self::ISBN_REGEX_PREFIX, '', $value);
                        $this->isbn = $normalised_isbn;
                    }

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
    public function lookup(BookServiceInterface $service)
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

