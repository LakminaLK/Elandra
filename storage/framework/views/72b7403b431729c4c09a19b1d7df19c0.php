

<?php $__env->startSection('content'); ?>
<div class="px-4 lg:px-6 py-6">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.category-brand-management');

$__html = app('livewire')->mount($__name, $__params, 'lw-2622505057-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/admin/category-brand/index.blade.php ENDPATH**/ ?>