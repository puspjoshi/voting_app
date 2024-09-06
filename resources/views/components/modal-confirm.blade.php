@props([
    'eventToOpenModal' =>null,
    'livewireEventToOpenModal'=>null,
    'eventToCloseModal',
    'modalTitle',
    'modalDescription',
    'modalConfirmButtonText',
    'wireClick'
])
<div
    x-cloak
    x-data = "{
            isOpen: false,
            hideModal(){
                this.isOpen = false
            }
        }
    "
    x-show = "isOpen"
    @keydown.escape.window = "isOpen = false"
    @if( $eventToOpenModal )
      {{ '@'.$eventToOpenModal }}.window = "
          isOpen = true
          $nextTick(() => $refs.confirmButton.focus())
      "
    @endif
    x-on:idea-was-deleted.window="
        hideModal()
    "
    x-on:idea-was-marked-as-spam.window="
        hideModal()
    "
    x-on:idea-was-marked-as-not-spam.window="
        hideModal()
    "
    x-on:comment-was-deleted.window="
        hideModal()
    "
    x-init="
      $wire.on('comment-was-updated',function(event){
          hideModal()
      })
      $wire.on('comment-was-marked-as-spam',function(event){
          hideModal()
      })
      $wire.on('comment-was-marked-as-not-spam',function(event){
          hideModal()
      })
      @if($livewireEventToOpenModal)    
          $wire.on('{{ $livewireEventToOpenModal }}',function(event){
              isOpen = true
          })
      @endif
    "
    class="relative z-20" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
  
    <div
        x-show = "isOpen"
        x-transition.origin.opacity.duration.300ms  
        class="fixed inset-0 z-10 w-screen overflow-y-auto"
    >
      <div class="flex items-end justify-center min-h-full p-4 text-center sm:items-center sm:p-0">
        
        <div 
            x-show = "isOpen"
            x-transition.origin.opacity.duration.300ms 
            class="relative overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:w-full sm:max-w-lg"
        >
          <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                <svg class="w-6 h-6 text-red" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">{{ $modalTitle }}</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">{{ $modalDescription }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="px-4 py-3 bg-gray-50 sm:flex sm:flex-row-reverse sm:px-6">
            
            <button type="button" wire:click="{{ $wireClick }}" x-ref="confirmButton" class="inline-flex justify-center w-full px-3 py-2 text-sm font-semibold text-white rounded-md shadow-sm bg-blue hover:bg-blue-hover sm:ml-3 sm:w-auto">{{ $modalConfirmButtonText }}</button>

            <button type="button"@click="isOpen = false" class="inline-flex justify-center w-full px-3 py-2 mt-3 text-sm font-semibold text-gray-900 bg-white rounded-md shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  