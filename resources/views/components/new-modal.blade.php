<div x-data="{ open: false }" class="transition-all flex justify-end ">
    <button x-on:click="open = ! open"
        class="transition-colors hover:bg-green-500 ease-linear transform-all bg-green-600 text-white rounded-xl px-3 text-xl">
        {{ $button }}
    </button>

    <div x-show.important="open" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div x-on:click.outside="open = false" class="max-w-[1000px] bg-gray-100 text-gray-950 p-6 rounded-lg shadow-lg">
            <div class="border-b-2 border-green-500 mb-5 ">
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $heading }}
                </h1>
            </div>
            <div class="mb-5 overflow-y-scroll max-h-[400px]">
                {{ $content }}
            </div>

            <div class="flex justify-end">
                @isset($footer)
                    {{ $footer }}
                @endisset
                <button x-on:click="open = false"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
            </div>
        </div>
    </div>

</div>
