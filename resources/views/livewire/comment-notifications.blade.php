<div 
    wire:poll='getNotificationCount' 
    x-data="{isOpen: false}" 
    class="relative"
>
    <button  @click=
        "
            isOpen = !isOpen
            if(isOpen){
                Livewire.dispatch('get-notifications')
            }            
        "
    >
        <svg viewBox="0 0 24 24" fill="currentColor" class="text-gray-400 w-7 h-7">
            <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z" clip-rule="evenodd" />
        </svg>
        @if($notificationCount)
            <div class="absolute flex items-center justify-center w-6 h-6 text-white border-2 rounded-full bg-red text-xxs -top-2 -right-2" >
                {{ $notificationCount }}
            </div>
        @endif
    </button>
    <ul 
        x-cloak
        x-show="isOpen"
        x-transition.origin.top
        @click.away = "isOpen = false"
        @keydown.escape.window = "isOpen = false"
        class="absolute z-10 py-3 overflow-y-auto text-sm bg-white left text- w-76 md:w-96 shadow-dialog rounded-xl max-h-126 -right-28 md:-right-12"
    >
        @if($notifications->isNotEmpty() && ! $isLoading)
            @foreach ( $notifications as $notification)
                <li>
                    <a
                        href="{{ route('idea.show', $notification->data['idea_slug']) }}"  
                        wire:click.prevent="markAsRead('{{ $notification->id }}')"
                        class="flex px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                    >
                        
                        <img src="{{ $notification->data['user_avatar'] }}" alt="avatar" class="w-10 h-10 rounded-full">
                        
                        <div class="ml-4">
                            <div class="line-clamp-6">
                                <span class="font-semibold">{{ $notification->data['user_name'] }}</span> commented on
                                <span class="font-semibold">{{ $notification->data['idea_title'] }}</span>:
                                <span>"{{ $notification->data['comment_body'] }}" </span>
                                
                            </div>
                            <div class="mt-2 text-xs text-gray-500"> {{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                </li>
            @endforeach
            
            <li class="text-center border border-gray-300">
                <button 
                    wire:click='markAllAsRead'
                    @click="isOpen = false"
                    class="block w-full py-3 font-semibold transition duration-150 ease-in hover:bg-gray-100 px-y"
                >
                    Mark all as read
                </button>
            </li>
        @elseif($isLoading)
        
            @foreach ( range(1,3) as $item)
                <li class="flex items-center px-5 py-3 transition duration-150 ease-in animate-pulse">
                    <div class="w-10 h-10 bg-gray-200 rounded-xl"></div>
                    <div class="flex-1 ml-4 space-y-2">
                        <div class="w-full h-4 bg-gray-200 rounded"></div>
                        <div class="w-full h-4 bg-gray-200 rounded"></div>
                        <div class="w-1/2 h-4 bg-gray-200 rounded"></div>
                    </div>
                </li>
            @endforeach
                
        @else
            <li class="py-6 mx-auto mt-12 w-44">
                <img src="{{ asset('img/no-ideas.svg')}}" class="mx-auto mix-blend-luminosity" />
                <div class="mt-6 font-bold text-center text-gray-400">No new notifications.....</div>

            </li>
        @endif
        
    </ul>
</div>