<div class="rounded-lg border border-[#e8decb] bg-[#faf8f5] p-4">
    <h6 class="mb-3 text-sm font-bold text-[#8c6d58]">
        6. Status DNRS
    </h6>

    <div class="flex items-center gap-2">
        <input
            type="checkbox"
            id="isDNRS"
            x-model="form.isDNRS"
            class="h-4 w-4 cursor-pointer rounded border-[#d4c5b3] text-[#5c4033] accent-[#d9534f] focus:ring-1 focus:ring-[#8c6d58]"
        />
        <label
            for="isDNRS"
            class="cursor-pointer select-none text-xs font-semibold text-red-600"
        >
            Status DNRS (Do Not Resuscitate / Jangan Resusitasi)
        </label>
    </div>

    <template x-if="errors.isDNRS">
        <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.isDNRS[0]"></div>
    </template>
</div>