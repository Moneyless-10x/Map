<div class="rounded-lg border border-[#e8decb] bg-[#faf8f5] p-4">
    <h6 class="mb-3 text-sm font-bold text-[#8c6d58]">
        4. Catatan Perawat Triase
    </h6>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Catatan & Anamnesis Perawat
            </label>
            <textarea
                x-model="form.catatanPerawat"
                rows="3"
                placeholder="Tuliskan hasil pengamatan perawat..."
                class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-2 text-xs text-[#333333] transition-colors focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
            ></textarea>
            
            <template x-if="errors.catatanPerawat">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.catatanPerawat[0]"></div>
            </template>
        </div>
    </div>
</div>