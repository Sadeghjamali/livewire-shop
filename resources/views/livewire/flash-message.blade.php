<div class="w-full flex items-center justify-between" x-data="{display:true}" dir="rtl">
    @if (session('success'))
        <div x-show="display"
             x-transition:leave="transition transform duration-5000"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-0"
             class="bg-green-100 text-sm text-green-600  rounded-lg text-right p-4 m-4 ">
            <strong>{{session('success')}}</strong>
            <button type="submit"
                    @click="display = false"
                    class="cursor-pointer p-1 rounded-sm bg-green-200 hover:bg-green-100">
                <svg class="size-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div x-show="display"
             x-transition:leave="transition transform duration-5000"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-0"
             class="bg-red-100 text-sm text-red-600  rounded-lg text-right p-4 m-4 ">
            <strong>{{session('error')}}</strong>
            <button type="submit"
                    @click="display = false"
                    class="cursor-pointer p-1 rounded-sm bg-red-200 hover:bg-red-100">
                <svg class="size-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif
</div>
