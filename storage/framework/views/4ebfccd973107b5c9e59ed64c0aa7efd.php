<div class="grid grid-cols-6 gap-6 mt-12 " x-data="{show:false}">

    <div class="bg-white shadow rounded-lg p-5 col-span-4">
        <table class="w-full">
            <thead class="h-10 border-b border-gray-200">
            <tr>
                <th class="text-left border-r border-gray-200">Product</th>
                <th class="text-left border-r border-gray-200">Size</th>
                <th class="text-left border-r border-gray-200">Color</th>
                <th class="text-left border-r border-gray-200">Price</th>
                <th class="text-left">Quantity</th>
                <th class="text-left">Total</th>
                <th class="px-3">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100 duration-200">
                    <td>
                        <p class="font-medium"><?php echo e($item->variant->product->name); ?></p>
                    </td>
                    <td><?php echo e($item->variant->size); ?></td>
                    <td><?php echo e($item->variant->color); ?></td>
                    <td>
                        <?php echo e(number_format($item->product->price , 0 , ',' , ',')); ?>

                        <small class="text-xs">تومان</small>
                    </td>
                    <td class="text-left">
                        <div class="flex items-center space-x-2">
                            <button wire:click="decrement(<?php echo e($item->id); ?>)" <?php if($item->quantity === 1): echo 'disabled'; endif; ?>>
                                <svg class="size-4" viewBox="0 0 24 24" xml:space="preserve">
                                <g>
                                    <circle fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" cx="12.0019" cy="12.0025" r="11.3822"/>
                                    <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-miterlimit="10" x1="5.4691" y1="12.0025" x2="18.5384" y2="12.0025"/>
                                </g>
                                    <rect x="0" y="0.001" fill="none" width="24" height="24"/>
                            </svg>
                            </button>
                            <div>
                                <?php echo e($item->quantity); ?>

                            </div>
                            <button wire:click="increment(<?php echo e($item->id); ?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     x="0px" y="0px" class="size-4" viewBox="0 0 24 24" xml:space="preserve">
                                <g>
                                    <circle fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" cx="11.9971" cy="12.0025" r="11.3822"/>
                                    <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-miterlimit="10" x1="11.9989" y1="5.4025" x2="11.9989" y2="18.6025"/>
                                    <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-miterlimit="10" x1="5.3989" y1="12.0025" x2="18.5989" y2="12.0025"/>
                                </g>
                                    <rect x="0" y="0.001" fill="none" width="24" height="24"/>
                            </svg>
                            </button>
                        </div>
                    </td>

                    <td class="text-left">
                        <?php echo e(number_format($item->subtotal , 0 , ',' , ',')); ?>

                        <small class="text-xs">تومان</small>
                    </td>

                    <td class="px-4 text-right">
                        <button wire:click="delete(<?php echo e($item->id); ?>)">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24"
                                 xml:space="preserve">
                            <g>
                                <polygon fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                         stroke-miterlimit="10"
                                         points="19.3775,23.5386 4.6036,23.5386 3.226,4.5305 20.755,4.5305"/>
                                <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-miterlimit="10" x1="1.1801" y1="4.5305" x2="22.8009" y2="4.5305"/>
                                <polygon fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                         stroke-miterlimit="10"
                                         points="14.2262,1.4921 9.7549,1.4921 8.7631,4.5305 15.218,4.5305"/>
                                <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-miterlimit="10" x1="11.9999" y1="7.5835" x2="11.9999" y2="20.4112"/>
                                <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-miterlimit="10" x1="16.5541" y1="7.5835" x2="15.7002" y2="20.4112"/>
                                <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-miterlimit="10" x1="7.4456" y1="7.5835" x2="8.2996" y2="20.4112"/>
                            </g>
                                <rect x="0" y="0" fill="none" width="24" height="24"/>
                        </svg>
                        </button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" class="text-right font-medium">Total:&nbsp;</td>

                <td class="font-medium">
                    <?php echo e(number_format($this->cart->total , 0 , ',' , ',')); ?>

                    <small class="text-xs">تومان</small>
                </td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="bg-white shadow rounded-lg p-5 col-span-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
            <p>Please <a class="underline font-bold" href="<?php echo e(route('register')); ?>">register</a> or <a
                    class="underline font-bold" href="<?php echo e(route('login')); ?>">login</a></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['wire:click' => 'initiatePayment','type' => 'submit','class' => 'w-full justify-center','wire:loading.attr' => 'disabled','wire:target' => 'initiatePayment']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'initiatePayment','type' => 'submit','class' => 'w-full justify-center','wire:loading.attr' => 'disabled','wire:target' => 'initiatePayment']); ?>
                    <span wire:loading.remove wire:target="initiatePayment"> پرداخت </span>
                    <span wire:loading wire:target="initiatePayment"> در حال اتصال به بانک...</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
            
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <!-- اسکریپت هدایت به درگاه -->
        <script>
            window.addEventListener('start-payment', event => {
                const url = event.detail.url;
                // هدایت به درگاه بانک
                window.location.href = url;
            });
        </script>
    </div>
</div>

<?php /**PATH F:\GIT-HUB-PATH_Projects\livewireShop\resources\views\livewire\cart.blade.php ENDPATH**/ ?>