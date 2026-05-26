<div class="grid grid-cols-2 gap-10 py-12 px-6">
    <div class="space-y-4" x-data="{ image:'http://127.0.0.1:8000/storage/{{$this->product->image->path}}' }">
        <div class="bg-white shadow rounded-lg p-5">
            <img x-bind:src="image" alt="">
        </div>

        <div class="grid grid-cols-4 gap-4">
            @foreach($this->product->images as $image)
                <div class="bg-white shadow rounded p-2">
                    <img src="{{asset('storage/'.$image->path)}}" @click="image = 'http://127.0.0.1:8000/storage/{{$image->path}}' " alt="">
                </div>
            @endforeach
        </div>
    </div>
    <div>
        <h1 class="text-4xl">{{$this->product->name}}</h1>
        <div class="text-gray-700 text-xl">
            {{number_format($product->price , 0 , ',','/')}}
            <small class="text-xs">تومان</small>
        </div>
        <p class="mt-4">{{$this->product->description}}</p>
        <div class="space-y-4 mt-4">
            <select wire:model="variant" class="block w-full rounded-lg border-0 py-1.5 pl-3 pr-10 text-gray-800">
                @foreach($this->product->variants as $variant)
                    <option value="{{$variant->id}}">{{$variant->size}}/{{$variant->color}}</option>
                @endforeach
            </select>
            @error('variant')
                <p class="text-red-600 text-xs">{{ $message }}</p>
            @enderror

            <x-button wire:click="addToCart">Add To Cart</x-button>
        </div>

    </div>
</div>

