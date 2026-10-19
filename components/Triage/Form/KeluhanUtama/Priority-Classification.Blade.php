<div class="rounded-lg border border-[#e8decb] bg-[#faf8f5] p-4">
    <h6 class="mb-3 text-sm font-bold text-[#8c6d58]">
        2. Klasifikasi Prioritas Triage
    </h6>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <!-- Prioritas Triage -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Prioritas Triage
            </label>
            <select
                x-model="form.priority"
                class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-1.5 text-xs text-[#333333] focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
            >
                <option value="P1">P1 - Emergency (Merah)</option>
                <option value="P2">P2 - Urgent (Kuning)</option>
                <option value="P3">P3 - Non-Urgent (Hijau)</option>
                <option value="P0">P0 - Deceased (Hitam)</option>
            </select>
            
            <template x-if="errors.priority">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.priority[0]"></div>
            </template>
        </div>

        <!-- Kategori Keluhan -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Kategori Keluhan
            </label>
            <input
                type="text"
                x-model="form.kategoriKeluhan"
                placeholder="Contoh: Sesak Napas, Nyeri Dada"
                class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-1.5 text-xs text-[#333333] focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
            />
            
            <template x-if="errors.kategoriKeluhan">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.kategoriKeluhan[0]"></div>
            </template>
        </div>
    </div>
</div>