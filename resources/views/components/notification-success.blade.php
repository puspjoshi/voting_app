@props([
    'type' => 'success',
    'redirect' =>false,
    'messageToDisplay' => '',
])
<div 
    x-cloak
    x-data = "
        {
            isOpen: false,
            isError: @if($type === 'success') false @elseif($type==='error') true @endif,
            messageToDisplay:'{{ $messageToDisplay }}',
            showNotification(message){
                this.isOpen = true
                this.messageToDisplay = message
                setTimeout(()=>{
                    this.isOpen = false
                },5000)
            },
        }
    "
    @if($redirect)
        x-init="
            $nextTick( () => showNotification(messageToDisplay) )
        "
    @else
        x-on:idea-was-updated.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:idea-was-deleted.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:idea-was-marked-as-spam.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:idea-was-marked-as-not-spam.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:comment-was-added.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:comment-was-updated.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:comment-was-deleted.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:comment-was-marked-as-spam.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:comment-was-marked-as-not-spam.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:status-was-updated.window="
            isError = false
            showNotification($event.detail.message)
        "
        x-on:status-was-updated-error.window="
            isError = true
            showNotification($event.detail.message)
        "
    @endif
    x-show = "isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-8"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-8"
    @keydown.escape.window = "isOpen = false"
    class="fixed bottom-0 right-0 z-20 flex justify-between w-full max-w-xs px-4 py-5 mx-2 my-8 bg-white border shadow-lg notes sm:max-w-sm rounded-xl sm:mx-6"
>
    <div class="flex items-center">
        
            <svg x-show="!isError" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        
            <svg x-show="isError" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        
        
        <span class="ml-2 text-sm font-semibold text-gray-500 sm:text-base" x-text="messageToDisplay"></span>
    </div>
    <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500">

        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
            
    </button>
</div>