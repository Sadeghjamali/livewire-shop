<div class="grid grid-cols-4 gap-4 mt-12 ">
    @foreach($this->products as $product)
        <div class="bg-white rounded-lg shadow p-4">
            <img class="transition-transform hover:scale-110 duration-300"
                 src="{{asset('storage/'.$product->image->path)}}" alt="{{$product->name}}">
            <div class="relative">
                <a wire:navigate href="{{route('product',$product)}}" class="absolute inset-0"></a>
                <h2 class="font-semibold text-lg">{{$product->name}}</h2>
                <span class="text-gray-700 text-sm font-medium">
                    {{number_format($product->price ,0,',','/')}}
                    <small class="text-xs">تومان</small>
                </span>
            </div>
        </div>
    @endforeach
</div>
