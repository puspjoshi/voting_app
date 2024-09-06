<div 
    x-cloak
    x-data = "{isOpen: false}"
    x-show = "isOpen"
    @keydown.escape.window = "isOpen = false"
    x-init="
        $wire.on('edit-comment-form', function(event) {
            isOpen = true
            $nextTick(()=>$refs.commentBody.focus())
        })
    "
    x-on:comment-was-updated="isOpen = false"
    class="relative z-10" 
    aria-labelledby="modal-title" 
    role="dialog" aria-modal="true"
>
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
  
    <div
        x-show = "isOpen"
        x-transition.opacity 
        class="fixed inset-0 z-10 w-screen overflow-y-auto"
    >
      <div class="flex items-end justify-center min-h-full ">
        
        <div
            x-show = "isOpen"
            x-transition.origin.bottom.duration.300ms 
            class="relative overflow-hidden text-left transition-all transform bg-white shadow-xl modal rounded-tl-xl rounded-tr-xl sm:my-8 sm:w-full sm:max-w-lg"
        >
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button
                    @click = "isOpen = false" 
                    class="text-gray-400 hover:text-gray-500"
                >
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                      
                </button>
            </div>
            <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
            <h3 class="text-lg font-medium text-center text-gray-900">Edit Comment</h3>

            <form wire:submit.prevent="updateComment" action="#" method="POST" class="px-4 py-6 space-y-4">
                
        
                <div>
                    <textarea x-ref="commentBody" wire:model.defer="body" name="body" id="body" class="w-full py-2 text-sm placeholder-gray-900 bg-gray-100 border-none rounded-xl text-smpx-4" cols="30" rows="4" placeholder="Type your comment here"></textarea>
                    @error('body')
                        <p class="mt-1 text-xs text-red"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="flex items-center justify-between space-x-3">
                    <button
                        type="button"
                        class="flex items-center justify-center w-1/2 text-xs font-semibold bg-gray-200 border border-gray-200 h-11 rounded-xl hover:border-gray-400"
                    >
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 text-gray-600 transform -rotate-45 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                        </svg>
                        <span class="ml-2">Attach </span>
            
                    </button>
                    <button
                        type="submit"
                        class="flex items-center justify-center w-1/2 px-6 py-3 text-xs font-semibold text-white border border-gray-200 hover:bg-blue h-11 bg-blue rounded-xl hover:border-gray-400"
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
