<div 
    x-data = "{ isOpen: false }"
    x-on:comment-was-added="
        isOpen = false   
    "
    x-init="
            
    "
    class="relative"
>
    <button
        @click=" 
            isOpen = !isOpen
            if(isOpen){
                $nextTick(()=>$refs.comment.focus())
            }
        " 
        type="button"
        class="flex items-center justify-center w-32 px-6 py-3 text-sm font-semibold text-white border border-gray-200 hover:bg-blue h-11 bg-blue rounded-xl hover:border-gray-400"
    >
        Reply
    </button>
    <div
        x-cloak
        x-show = "isOpen"
        x-transition.origin.top.left.duration.500ms
        @click.away="isOpen = false"
        @keydown.escape.window = "isOpen = false"
        class="absolute z-10 mt-2 text-sm font-semibold text-left bg-white w-76 md:w-104 shadow-dialog rounded-xl"
    >
        @auth
            <form wire:submit.prevent="addComment" action="#" class="px-4 py-6 space-y-4">
                <div>
                    <textarea wire:model="comment" x-ref="comment" name="post-comment" id="post-comment" cols="30" rows="4" class="w-full px-4 py-2 text-sm placeholder-gray-900 bg-gray-100 border-none rounded-xl" placeholder="Go ahead, don't be shy. Share your thoughts..."></textarea>
                    @error('comment')
                        <p class="mt-1 text-xs text-red"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="flex flex-col items-center md:flex-row md:space-x-3">
                    <button 
                        type="submit"
                        class="flex items-center justify-center w-full px-6 py-3 text-sm font-semibold text-white border border-gray-200 md:w-1/2 h-11 hover:bg-blue bg-blue rounded-xl hover:border-gray-400"
                    >
                        Post comments
                    </button>
                    <button
                        type="button"
                        class="flex items-center justify-center w-full mt-2 text-xs font-semibold bg-gray-200 border border-gray-200 md:w-32 h-11 rounded-xl hover:border-gray-400 md:mt-0"
                    >
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 text-gray-600 transform -rotate-45 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                        </svg>
                        <span class="ml-2">Attach </span>

                    </button>
                </div>
            </form>
        @else
            <div class="px-4 py-6">
                <p class="font-normal">
                    Please login or create an account to post a comment.
                </p>
                <div class="flex items-center mt-8 space-x-3">
                    <a 
                        wire:click.prevent="redirectToLogin"
                        href="{{ route('login')}}" 
                        class="w-1/2 px-6 py-3 text-sm font-semibold text-center text-white transition duration-150 ease-in h-11 bg-blue rounded-xl hover:bg-blue-hover" 
                    >Login </a>
                    <a 
                        wire:click.prevent="redirectToRegister"
                        href="{{ route('register')}}"
                        class="flex items-center justify-center w-1/2 px-6 py-3 text-xs font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 h-11 rounded-xl hover:border-gray-400"
                    >Sign up</a>
                </div>
            </div>
        @endauth
    </div>
</div>
@script
<script>
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            
                if(['gotoPage','nextPage','previousPage'].includes(commit.calls[0].method)){
                    
                    const firstComment = document.querySelector('.comment-container:first-child');
                    
                    firstComment.scrollIntoView({ behavior: 'smooth' });
                }
            
                if(['comment-was-added','status-was-updated'].includes(commit.calls[0].params[0])
                && component.name === 'idea-comments'){

                    Livewire.hook('morph.added',  ({ el }) => {
                        console.log(el);
                        el.scrollIntoView({ behavior: 'smooth' });

                        el.classList.add('bg-green');
                    
                        setTimeout(() => {
                            el.classList.remove('bg-green');
                        }, 5000);
                    })    
                } 
            })
        
</script>
@endscript

@if(session('scrollToCommentTo'))
    @script
    <script>
        const commentToScrollTo = document.querySelector('#comment-{{ session('scrollToCommentTo') }}');
        commentToScrollTo.scrollIntoView({ behavior: 'smooth' });
        console.log(commentToScrollTo)
        commentToScrollTo.classList.remove('bg-white');
        commentToScrollTo.classList.add('bg-green-50');
    
        setTimeout(() => {
            commentToScrollTo.classList.remove('bg-green-50');
            commentToScrollTo.classList.add('bg-white');
        }, 5000);
    </script>
    @endscript           
@endif