<div class="rounded-lg border border-[#e8decb] bg-[#faf8f5] p-4">
    <h6 class="mb-3 text-sm font-bold text-[#8c6d58]">
        3. Upload Foto Triase & Trauma
    </h6>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <!-- Foto Triase -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Foto Triase
            </label>
            <input
                type="file"
                accept="image/*"
                @change="form.foto_triase = $event.target.files[0]"
                class="w-full rounded border border-[#d4c5b3] bg-white px-2 py-1 text-xs text-[#333333] file:mr-3 file:rounded file:border-0 file:bg-[#f0ebe5] file:px-2.5 file:py-1 file:text-xs file:font-semibold file:text-[#5c4033] hover:file:bg-[#e8decb] focus:outline-none"
            />
            <template x-if="errors.foto_triase">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.foto_triase[0]"></div>
            </template>
        </div>

        <!-- Foto Trauma -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Foto Trauma
            </label>
            <input
                type="file"
                accept="image/*"
                @change="form.foto_trauma = $event.target.files[0]"
                class="w-full rounded border border-[#d4c5b3] bg-white px-2 py-1 text-xs text-[#333333] file:mr-3 file:rounded file:border-0 file:bg-[#f0ebe5] file:px-2.5 file:py-1 file:text-xs file:font-semibold file:text-[#5c4033] hover:file:bg-[#e8decb] focus:outline-none"
            />
            <template x-if="errors.foto_trauma">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.foto_trauma[0]"></div>
            </template>
        </div>

        <!-- Dokumen Pendukung -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Dokumen Pendukung
            </label>
            <input
                type="file"
                accept="image/*,.pdf"
                @change="form.foto_trauma_upload = $event.target.files[0]"
                class="w-full rounded border border-[#d4c5b3] bg-white px-2 py-1 text-xs text-[#333333] file:mr-3 file:rounded file:border-0 file:bg-[#f0ebe5] file:px-2.5 file:py-1 file:text-xs file:font-semibold file:text-[#5c4033] hover:file:bg-[#e8decb] focus:outline-none"
            />
            <template x-if="errors.foto_trauma_upload">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.foto_trauma_upload[0]"></div>
            </template>
        </div>
    </div>
</div>