<div
    class="relative"
    x-data="{ isOpen:false }"
    x-init="
        $wire.on('status-was-updated', data => {
            isOpen = false;
        })
        
        $wire.on('status-was-updated-error', data => {
            isOpen = false;
        })
    "
>
@admin
    <button
        @click = "isOpen = !isOpen"
        type="button"
        class="flex items-center justify-center mt-2 text-sm font-semibold bg-gray-200 border border-gray-200 w-36 h-11 rounded-xl hover:border-gray-400 md:mt-0"
    >
        <span>Set Status </span>
        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 ml-2 size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>
    <div
        x-cloak
        x-show="isOpen"
        @click.away="isOpen = false"
        @keydown.escape.window = "isOpen = false"
        x-transition.origin.top.left.duration.500ms 

        class="absolute z-10 mt-2 text-sm font-semibold text-left bg-white w-76 shadow-dialog rounded-xl"
    >
        <form wire:submit.prevent="setStatus" action="#" class="px-4 py-6 space-y-4">
            <div class="space-y-2">
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" class="text-gray-600 bg-gray-200 border-none" name="status" value="1" checked>
                        <span class="ml-2">Open</span>
                    </label>
                </div>
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" class="bg-gray-200 border-none text-purple" name="status" value="2">
                        <span class="ml-2">Implemented</span>
                    </label>
                </div>
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" class="bg-gray-200 border-none text-yellow" name="status" value="3">
                        <span class="ml-2">In progress</span>
                    </label>
                </div>
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio"  class="bg-gray-200 border-none text-green" name="status" value="4">
                        <span class="ml-2">Considering</span>
                    </label>
                </div>
                <div>
                    <label for="" class="inline-flex items-center">
                        <input wire:model="status" type="radio" class="bg-gray-200 border-none text-red" name="status" value="5">
                        <span class="ml-2">Closed</span>
                    </label>
                </div>
            </div>
            <div>
                <textarea wire:model='comment' name="post-comment" id="post-comment" cols="30" rows="4" class="w-full px-4 py-2 text-sm placeholder-gray-900 bg-gray-100 border-none rounded-xl" placeholder="Add an update comment (optional)..."></textarea>
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
                    class="flex items-center justify-center w-1/2 px-6 py-3 text-xs font-semibold text-white border border-gray-200 hover:bg-blue h-11 bg-blue rounded-xl hover:border-gray-400 disabled:opacity-50"
                >
                    <span class="ml-2">Update </span>
                </button>
            </div>
            <div>
                <label for="" class="inline-flex items-center font-normal">
                    <input wire:model="notifyAllVoters" type="checkbox" name="notify_voters" class="bg-gray-200 rounded">
                    <span class="ml-2">Notify all voters</span>
                </label>
            </div>

        </form>
    </div>
@endadmin
</div>