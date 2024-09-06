<div>
    <div class="flex flex-col space-y-3 filters md:flex-row md:space-y-0 md:space-x-6">
        <div class="w-full md:w-1/3">
            <select wire:model.change="category" name="category" id="category" class="w-full px-4 py-2 border-none rounded-xl">
                <option value="All Categories">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/3">
            <select wire:model.change="filter" name="other-filters" id="other-filters" class="w-full px-4 py-2 border-none rounded-xl">
                <option value="No Filter">No Filter</option>
                <option value="Most Voted">Most Voted</option>
                <option value="My Ideas">My Ideas</option>
                @admin
                    <option value="Spam Ideas">Spam Ideas</option>
                    <option value="Spam Comments">Spam Comments</option>
                @endadmin
            </select>
        </div>
        <div class="relative w-full md:w-2/3">
            

            <input wire:model.change="search" type="search" placeholder="Find an idea" class="w-full px-4 py-2 pl-8 placeholder-gray-900 bg-white border-none rounded-xl" >
            <div class="absolute top-0 flex items-center h-full ml-2">
                <svg class="w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </div>

    </div> <!-- End filters -->
    <div class="my-6 space-y-6 ideas-container">
    @forelse($ideas as $idea)
        <livewire:idea-index 
            :key="$idea->id"
            :idea="$idea"
            :votesCount="$idea->votes_count"
        />    
    @empty
        <div class="mx-auto mt-12 w-70">
            <img src="{{ asset('img/no-ideas.svg')}}" class="mx-auto mix-blend-luminosity" />
            <div class="mt-6 font-bold text-center text-gray-400">No ideas were found.....</div>

        </div>
    @endforelse
        
    </div><!--end ideas container -->
    <div class="my-8">
        {{$ideas->links()}}
    </div>
</div>