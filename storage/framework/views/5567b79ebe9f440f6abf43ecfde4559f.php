<!-- Map Header & Controls -->
<div class="mt-6 flex flex-wrap items-center justify-between gap-2.5">
    <h4 class="m-0 text-sm font-semibold text-[#4a3525]">
        Peta Lokasi
    </h4>
    <div class="flex items-center gap-2">
        <input type="text" x-model="hospitalSearch" placeholder="Cari Nama Rumah Sakit..."
            class="w-60 rounded border border-[#d4c5b3] px-3 py-1.5 text-xs outline-none focus:border-[#5c4033]" />
        <button type="button" x-show="hospitalSearch" @click="hospitalSearch = ''"
            class="cursor-pointer rounded border border-[#d4c5b3] bg-[#f5f0eb] px-2.5 py-1.5 text-[11px] text-[#4a3525] hover:bg-[#e8decb]">
            Reset
        </button>
    </div>
</div>

<!-- Leaflet Map Component -->
<?php if (isset($component)) { $__componentOriginal2fe74a29c4501199e36689b9e75ae92e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2fe74a29c4501199e36689b9e75ae92e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.triage.spgdt.form.map','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('triage.spgdt.form.map'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2fe74a29c4501199e36689b9e75ae92e)): ?>
<?php $attributes = $__attributesOriginal2fe74a29c4501199e36689b9e75ae92e; ?>
<?php unset($__attributesOriginal2fe74a29c4501199e36689b9e75ae92e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2fe74a29c4501199e36689b9e75ae92e)): ?>
<?php $component = $__componentOriginal2fe74a29c4501199e36689b9e75ae92e; ?>
<?php unset($__componentOriginal2fe74a29c4501199e36689b9e75ae92e); ?>
<?php endif; ?>

<!-- Add Hospital Component -->
<?php if (isset($component)) { $__componentOriginalcdddb957c2e922265804598f3a2874f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdddb957c2e922265804598f3a2874f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.triage.add-hospital','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('triage.add-hospital'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcdddb957c2e922265804598f3a2874f3)): ?>
<?php $attributes = $__attributesOriginalcdddb957c2e922265804598f3a2874f3; ?>
<?php unset($__attributesOriginalcdddb957c2e922265804598f3a2874f3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcdddb957c2e922265804598f3a2874f3)): ?>
<?php $component = $__componentOriginalcdddb957c2e922265804598f3a2874f3; ?>
<?php unset($__componentOriginalcdddb957c2e922265804598f3a2874f3); ?>
<?php endif; ?><?php /**PATH C:\Users\HP\Desktop\sCReaM\CRM\resources\views/components/triage/spgdt/form/table.blade.php ENDPATH**/ ?>