<div class="w-full max-w-xl m-auto mt-8">

    <h1>ISBN Book Lookup</h1>

    <!-- Form -->
    <form wire:submit.prevent="lookup" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="isbn">
                Isbn
            </label>
            <input id="isbn" name="isbn" type="text" wire:model="isbn" placeholder="Enter ISBN" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('isbn')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">Search</button>
    </form>

    <!-- Error messages -->
    @if($error)
        <p>{{ $error }}</p>
    @endif

    <!-- Loading -->
    <div wire:loading wire:target="lookup">
        Search book...
    </div>

    <!-- Result card -->
    @if($book)
        <div wire:loading.remove class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2>Result:</h2>
            <hr/>
            <p><b>Title:</b> {{ $book['title'] }}</p>
            <p><b>Author(s):</b> {{ implode(', ', $book['authors']) }}</p>
            <p><b>Published date:</b> {{ $book['published_date'] }}</p>
            <p><b>Description:</b> <br>{{ $book['description'] }}</p>
        </div>
    @endif

</div>
