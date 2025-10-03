<div class="relative">
    <!--[if BLOCK]><![endif]--><?php if($count > 0): ?>
        <!--[if BLOCK]><![endif]--><?php if($mobile): ?>
            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full shadow-sm">
                <?php echo e($count); ?>

            </span>
        <?php else: ?>
            <span class="absolute top-1 -right-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-white shadow-lg">
                <?php echo e($count); ?>

            </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/livewire/cart-count.blade.php ENDPATH**/ ?>