<div 
    x-cloak
    x-data = "{isOpen: false}"
    x-show = "isOpen"
    @keydown.escape.window = "isOpen = false"
    @custom-show-edit-modal.window = "
        isOpen = true
        $nextTick(()=>$refs.title.focus())
    "
    x-on:idea-was-updated="isOpen = false"
    class="relative z-10" 
    aria-labelledby="modal-title" 
    role="dialog" aria-modal="true"
>
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
  
    <div
        x-show = "isOpen"
        x-transition.opacity 
        class="fixed inset-0 z-10 w-screen overflow-y-auto"
    >
      <div class="flex min-h-full items-end justify-center ">
        
        <div
            x-show = "isOpen"
            x-transition.origin.bottom.duration.300ms 
            class="modal relative transform overflow-hidden rounded-tl-xl rounded-tr-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
        >
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button
                    @click = "isOpen = false" 
                    class="text-gray-400 hover:text-gray-500"
                >
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                      
                </button>
            </div>
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <h3 class="text-center text-lg font-medium text-gray-900">Edit Idea</h3>
            <p class="text-xs text-center leading-4 text-gray-500 px-6 mt-4">You have one hour to edit your idea from the time you created it.</p>

            <form wire:submit.prevent="updateIdea" action="#" method="POST" class="space-y-4 px-4 py-6">
                <div>
                    <input wire:model.defer="title" x-ref="title" type="text" class="w-full text-sm bg-gray-100 border-none rounded-xl placeholder-gray-900 px-4 py-2" placeholder="Your Idea" />
                    @error('title')
                        <p class="text-red text-xs mt-1"> {{ $message }} </p>
                    @enderror
                </div>
                <div>
                    <select wire:model.defer="category" name="category_add" id="category_add" class="w-full rounded-xl bg-gray-100 text-sm border-none px-4 py-2">
                        @foreach($categories as $category)        
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red text-xs mt-1"> {{ $message }} </p>
                    @enderror
                </div>
                <div>
                    <textarea wire:model.defer="description" name="idea" id="idea" class="w-full bg-gray-100 text-sm rounded-xl border-none placeholder-gray-900 text-smpx-4 py-2" cols="30" rows="4" placeholder="Describe your idea"></textarea>
                    @error('description')
                        <p class="text-red text-xs mt-1"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="flex items-center justify-between space-x-3">
                    <button
                        type="button"
                        class="flex items-center justify-center w-1/2 h-11 text-xs bg-gray-200 font-semibold rounded-xl border border-gray-200 hover:border-gray-400"
                    >
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 text-gray-600 transform -rotate-45">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                        </svg>
                        <span class="ml-2">Attach </span>
            
                    </button>
                    <button
                        type="submit"
                        class="flex items-center hover:bg-blue text-white justify-center w-1/2 h-11 text-xs bg-blue font-semibold rounded-xl border border-gray-200 hover:border-gray-400 px-6 py-3"
                    >
                        <span class="ml-2">Submit </span>
                    </button>
                </div>
                
            </form>
            
          </div>
          
        </div>
      </div>
    </div>
  </div>
