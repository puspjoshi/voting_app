<div>
    @auth
        <form wire:submit.prevent="createIdea" action="#" method="POST" class="px-4 py-6 space-y-4">
            <div>
                <input wire:model.defer="title" type="text" class="w-full px-4 py-2 text-sm placeholder-gray-900 bg-gray-100 border-none rounded-xl" placeholder="Your Idea" />
                @error('title')
                    <p class="mt-1 text-xs text-red"> {{ $message }} </p>
                @enderror
            </div>
            <div>
                <select wire:model.defer="category" name="category_add" id="category_add" class="w-full px-4 py-2 text-sm bg-gray-100 border-none rounded-xl">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1 text-xs text-red"> {{ $message }} </p>
                @enderror
            </div>
            <div>
                <textarea wire:model.defer="description" name="idea" id="idea" class="w-full py-2 text-sm placeholder-gray-900 bg-gray-100 border-none rounded-xl text-smpx-4" cols="30" rows="4" placeholder="Describe your idea"></textarea>
                @error('description')
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
    @else
        <div class="my-6 text-center">
            <a
                wire:click.prevent="redirectToLogin"
                href="{{ route('login') }}"
                class="justify-center inline-block w-1/2 px-6 py-3 text-xs font-semibold text-white transition duration-150 ease-in border border-gray-200 hover:bg-blue h-11 bg-blue rounded-xl hover:border-gray-400"
            >Login</a>

            <a
                wire:click.prevent="redirectToRegister"
                href="{{ route('register') }}"
                class="justify-center inline-block w-1/2 px-6 py-3 mt-4 text-xs font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 h-11 rounded-xl hover:border-gray-400"
            >Signup</a>

        </div>
    @endauth
</div>