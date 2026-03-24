<div class="w-full max-w-xl m-auto mt-8">

    <h1>ISBN Book Lookup</h1>

    <!-- Sample data -->
    <p class="mb-2">A few ISBN's for testing:</p>
    <p class="mb-2">
        9781451648546<br>
        9780007259762<br>
        ISBN 978-0-596-52068-7<br>
        ISBN-13: 978-0-596-52068-7<br>

    </p>

    <!-- Form -->
    <form wire:submit.prevent="lookup" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="isbn">
                ISBN
            </label>
            <input id="isbn" name="isbn" type="text" wire:model="isbn" placeholder="Enter ISBN" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('isbn')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">Search</button>
        <button wire:click="clearResults" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">Clear</button>
    </form>

    <!-- Error messages -->
    <div wire:loading.remove>
        @if($error)
            <p>{{ $error }}</p>
        @endif
    </div>

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
