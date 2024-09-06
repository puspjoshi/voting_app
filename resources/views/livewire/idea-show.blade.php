<div class="container idea-and-buttons">
    <div class="flex mt-4 bg-white idea-container rounded-xl">
        <div class="flex flex-col flex-1 px-4 py-6 md:flex-row">
            <div class="flex-none mx-2">
                <a href="#">
                    <img src="{{ $idea->user->getAvatar() }}" class="w-14 h-14 rounded-xl"  alt="avatar" />
                </a>
            </div>
            <div class="w-full mx-2 md:mx-4 ">
                <h4 class="mt-2 text-xl font-semibold md:mt-0">
                    {{ $idea->title }}
                </h4>
                <div class="mt-3 text-gray-600">
                    @admin
                        @if($idea->spam_reports > 0)
                            <div class="mb-2 text-red">Spam Reports: {{ $idea->spam_reports }}</div>
                        @endif
                    @endadmin
                    {!! nl2br(e($idea->description)) !!}
                </div>
                <div class="flex flex-col justify-between mt-6 md:flex-row md:items-center">
                    <div class="flex items-center space-x-2 text-xs font-semibold text-gray-400">
                        <div class="hidden font-bold text-gray-900 md:block ">{{ $idea->user->name}}</div>
                        <div class="hidden md:block">&bull;</div>
                        <div>{{ $idea->created_at->diffForHumans(); }}</div>
                        <div>&bull;</div>
                        <div>{{ $idea->category->name }}</div>
                        <div>&bull;</div>
                        <div wire:ignore class="text-gray-800">{{ $idea->comments()->count() }} Comments</div>
                    </div>
                    <div
                        x-data="{isOpen: false}" 
                        class="flex items-center mt-4 space-x-2 md:mt-0"
                    >
                        <div class="{{ 'status-'.Str::kebab($idea->status->name )}} text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">
                            {{$idea->status->name}}
                        </div>
                        <div class="relative">
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
                                    
                                </button>
                                <ul 
                                    x-cloak
                                    x-show="isOpen"
                                    @click.away = "isOpen = false"
                                    @keydown.escape.window = "isOpen = false"
                                    class="absolute right-0 z-10 py-3 font-semibold text-left bg-white w-44 shadow-dialog rounded-xl top-8 md:ml-8 md:top-6 md:left-0"
                                >
                                @can('update', $idea)    
                                    <li>
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    isOpen = false
                                                    $dispatch('custom-show-edit-modal')
                                                "  
                                                class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                            >Edit Idea</a>
                                        </li>
                                    @endcan
                                    @can('delete', $idea) 
                                        <li>
                                                <a
                                                    href="#"
                                                    @click.prevent="
                                                        isOpen = false
                                                        $dispatch('custom-show-delete-modal')
                                                    "  
                                                    class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                                >Delete Idea</a>
                                            </li>
                                        @endcan
                                        <li>
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    isOpen = false
                                                    $dispatch('custom-mark-as-spam-modal')
                                                "  
                                                class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                            >Mark as Spam</a>
                                        </li>
                                    @admin
                                        @if($idea->spam_reports > 0)
                                            <li>
                                                <a
                                                    href="#"
                                                    @click.prevent="
                                                        isOpen = false
                                                        $dispatch('custom-mark-as-not-spam-modal')
                                                    "  
                                                    class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                                >Not Spam</a>
                                            </li>
                                            @endif
                                    @endadmin
                                </ul>
                            @endauth
                        </div>
                    </div>
                    <div class="flex items-center mt-4 md:hidden md:mt-0">
                            <div class="h-10 px-4 py-2 pr-8 text-center bg-gray-100 rounded-xl">
                                <div class="text-sm font-bold leading-none @if($hasVoted) text-blue @endif">{{ $votesCount }}</div>
                                <div class="font-semibold text-xxs leadine-none test-gray-400">Votes</div>
                            </div>
                            @if($hasVoted)
                                <button 
                                wire:click.prevent="vote"
                                class="w-20 px-4 py-3 -mx-5 font-bold text-white uppercase transition duration-150 ease-in border bg-blue border-blue hover:border-blue text-xxs rounded-xl">Voted</button>
                            @else
                                <button 
                                    wire:click.prevent="vote"
                                    class="w-20 px-4 py-3 -mx-5 font-bold uppercase transition duration-150 ease-in bg-gray-200 border border-gray-200 hover:border-gray-400 text-xxs rounded-xl">
                                    Vote
                                </button>
                            @endif
                            
                        </div>
                    
                </div>
            </div>
        </div>
    </div> <!-- end idea container -->
    <div class="flex items-center justify-between mt-6 buttons-container">
        <div
               
            class="flex flex-col items-center ml-6 space-x-4 md:flex-row"
        >
            <livewire:add-comment :idea="$idea" />
            
            <livewire:set-status :idea="$idea"/>
        </div>
        <div class="items-center hidden ml-6 space-x-4 md:flex">
            <div class="px-3 py-2 bg-white font-semibold-text-center rounded-xl">
                <div class="text-xl font-bold leading-none @if($hasVoted) text-blue @endif">{{ $votesCount }}</div>
                <div class="text-xs leading-none text-gray-400">Votes</div>
            </div>
            @if($hasVoted)
                <button
                    wire:click.prevent="vote"
                    type="button"
                    class="text-xs font-semibold text-white uppercase transition duration-150 ease-in border w-36 h-11 bg-blue rounded-xl border-blue hover:bg-blue-hover"
                >
                <span>Voted </span>
                
            </button>
            @else
                <button
                    wire:click.prevent="vote"
                    type="button"
                    class="text-xs font-semibold uppercase transition duration-150 ease-in bg-gray-200 border border-gray-200 w-36 h-11 rounded-xl hover:border-gray-400"
                >
                    <span>Vote </span>
                    
                </button>
            @endif
        </div>
    </div><!-- end button container -->
</div> <!-- end idea and button container -->