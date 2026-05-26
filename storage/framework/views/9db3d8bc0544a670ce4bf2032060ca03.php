<div class="grid grid-cols-4 gap-4 mt-12 ">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg shadow p-4">
            <img class="transition-transform hover:scale-110 duration-300"
                 src="<?php echo e(asset('storage/'.$product->image->path)); ?>" alt="<?php echo e($product->name); ?>">
            <div class="relative">
                <a wire:navigate href="<?php echo e(route('product',$product)); ?>" class="absolute inset-0"></a>
                <h2 class="font-semibold text-lg"><?php echo e($product->name); ?></h2>
                <span class="text-gray-700 text-sm font-medium">
                    <?php echo e(number_format($product->price ,0,',','/')); ?>

                    <small class="text-xs">تومان</small>
                </span>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH F:\GIT-HUB-PATH_Projects\livewireShop\resources\views/livewire/front-store.blade.php ENDPATH**/ ?>