<div
    id="comment-{{$comment->id}}"
    class="relative flex mt-4 transition duration-150 ease-in bg-white comment-container rounded-xl @if($comment->is_status_update) is-status-update {{ 'status-'.Str::kebab($comment->status->name )}} @endif"
>
    <div class="flex flex-col flex-1 px-4 py-6 md:flex-row">
        <div class="flex-none">
            <a href="#">
                <img src="{{ $comment->user->getAvatar() }}" class="w-14 h-14 rounded-xl"  alt="avatar" />
            </a>
            @if($comment->user->isAdmin())
                <div class="mt-1 font-bold text-center uppercase text-blue text-xxs">
                    Admin
                </div>
            @endif
        </div>
        <div class="w-full md:mx-4">
            
            <div class="text-gray-600">
                @admin
                    @if($comment->spam_reports > 0)
                        <div class="mb-2 text-red">Spam Reports: {{ $comment->spam_reports }}</div>
                    @endif
                @endadmin
                
                @if($comment->is_status_update)
                    <h4 class="mb-3 text-xl font-semibold">
                        Status changed to "{{ $comment->status->name }}"
                    </h4>
                @endif
            
                <div>
                    {!! nl2br(e($comment->body)) !!}
                </div> 
            </div>
            <div class="flex items-center justify-between mt-6">
                <div class="flex items-center space-x-2 text-xs font-semibold text-gray-400">
                    <div class="font-bold @if($comment->is_status_update) text-blue @else text-gray-900 @endif">
                        {{ $comment->user->name }}
                    </div>
                    <div>&bull;</div>
                    
                    @if($comment->user->id === $ideaUserId)
                        <div class="px-3 bg-gray-100 border rounded-full py1">OP</div>
                        <div>&bull;</div>
                    @endif
                    <div>{{ $comment->created_at->diffForHumans() }}</div>
                    
                </div>
                <div
                    x-data="{isOpen: false}"
                    class="flex items-center space-x-2 text-gray-900"
                >
                    @auth
                        <button
                            @click="isOpen = !isOpen" 
                            class="relative px-3 py-2 transition duration-150 ease-in bg-gray-100 border rounded-full hover:bg-gray-200 h-7"
                        >
                            <svg fill="#000000" height="6px" width="24px"  
                                viewBox="0 0 32.055 32.055" xml:space="preserve">
                            <g>
                                <path d="M3.968,12.061C1.775,12.061,0,13.835,0,16.027c0,2.192,1.773,3.967,3.968,3.967c2.189,0,3.966-1.772,3.966-3.967
                                    C7.934,13.835,6.157,12.061,3.968,12.061z M16.233,12.061c-2.188,0-3.968,1.773-3.968,3.965c0,2.192,1.778,3.967,3.968,3.967
                                    s3.97-1.772,3.97-3.967C20.201,13.835,18.423,12.061,16.233,12.061z M28.09,12.061c-2.192,0-3.969,1.774-3.969,3.967
                                    c0,2.19,1.774,3.965,3.969,3.965c2.188,0,3.965-1.772,3.965-3.965S30.278,12.061,28.09,12.061z"/>
                            </g>
                            </svg>
                            <ul 
                                x-cloak
                                x-show="isOpen"
                                x-transition.origin.top.left
                                @click.away = "isOpen = false"
                                @keydown.escape.window = "isOpen = false"
                                class="absolute right-0 z-10 py-3 font-semibold text-left bg-white w-44 shadow-dialog rounded-xl top-8 md:ml-8 md:top-6 md:left-0"
                            >
                                @can('update', $comment)    
                                    <li>
                                        <a
                                            href="#"
                                            @click.prevent="
                                                isOpen = false
                                                Livewire.dispatch('set-edit-comment',{commentId: {{ $comment->id }}})
                                            "  
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                        >Edit Comment</a>
                                    </li>
                                @endcan
                                @can('delete', $comment) 
                                    <li>
                                        <a
                                            href="#"
                                            @click.prevent="
                                                isOpen = false
                                                Livewire.dispatch('set-delete-comment',{commentId: {{ $comment->id }}})
                                            "  
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                        >Delete Comment</a>
                                        </li>
                                @endcan
                                <li>
                                    <a
                                        href="#"
                                        @click.prevent="
                                            isOpen = false
                                            Livewire.dispatch('set-mark-as-a-spam-comment',{commentId: {{ $comment->id }}})
                                        "  
                                        class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                    >Mark as Spam</a>
                                </li>
                                @admin
                                    @if($comment->spam_reports > 0)
                                        <li>
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    isOpen = false
                                                    Livewire.dispatch('set-mark-as-not-spam-comment',{commentId: {{ $comment->id }}})
                                                "  
                                                class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                            >Not Spam</a>
                                        </li>
                                    @endif
                                @endadmin
                            </ul>
                        </button>
                    @endauth
                </div>
                
            </div>
        </div>
    </div>
</div> <!-- end comment container -->