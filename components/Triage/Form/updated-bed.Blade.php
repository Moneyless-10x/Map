<div class="rounded-lg border border-[#e8decb] bg-[#faf8f5] p-4">
    <h6 class="mb-3 text-sm font-bold text-[#8c6d58]">
        5. Update Bed / Ruangan Tujuan
    </h6>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Pilih Bed Tujuan
            </label>
            <select
                x-model="form.bedUpdated"
                class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-1.5 text-xs text-[#333333] transition-colors focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
            >
                <option value="">-- Pilih Bed / Ruangan --</option>
                <option value="Bed 01 (Resusitasi)">Bed 01 (Resusitasi)</option>
                <option value="Bed 02 (Tindakan)">Bed 02 (Tindakan)</option>
                <option value="Bed 03 (Observasi)">Bed 03 (Observasi)</option>
                <option value="Ruang Transit">Ruang Transit</option>
            </select>

            <template x-if="errors.bedUpdated">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.bedUpdated[0]"></div>
            </template>
        </div>
    </div>
</div>