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
<x-triage.spgdt.form.map />

<!-- Add Hospital Component -->
<x-triage.add-hospital />