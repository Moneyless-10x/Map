<div class="rounded-lg border border-[#e8decb] bg-[#faf8f5] p-4">
    <h6 class="mb-3 text-sm font-bold text-[#8c6d58]">
        1. Informasi Kedatangan & Pasien
    </h6>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <!-- Tanggal Masuk -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Tanggal Masuk
            </label>
            <input
                type="date"
                x-model="form.tanggalMasuk"
                class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-1.5 text-xs text-[#333333] focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
            />
            <template x-if="errors.tanggalMasuk">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.tanggalMasuk[0]"></div>
            </template>
        </div>

        <!-- Waktu Kedatangan -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Waktu Kedatangan
            </label>
            <input
                type="time"
                x-model="form.waktuKedatangan"
                class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-1.5 text-xs text-[#333333] focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
            />
            <template x-if="errors.waktuKedatangan">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.waktuKedatangan[0]"></div>
            </template>
        </div>

        <!-- Nama Pasien -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                Nama Pasien
            </label>
            <input
                type="text"
                x-model="form.namaPasien"
                placeholder="Masukkan nama pasien"
                class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-1.5 text-xs text-[#333333] focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
            />
            <template x-if="errors.namaPasien">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.namaPasien[0]"></div>
            </template>
        </div>

        <!-- No. Rekam Medis -->
        <div>
            <label class="mb-1 block text-xs font-semibold text-[#4a3525]">
                No. Rekam Medis
            </label>
            <input
                type="text"
                x-model="form.rekamMedis"
                placeholder="Contoh: RM-12345"
                class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-1.5 text-xs text-[#333333] focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
            />
            <template x-if="errors.rekamMedis">
                <div class="mt-1 text-[11px] font-medium text-red-600" x-text="errors.rekamMedis[0]"></div>
            </template>
        </div>

        <!-- Unknown Patient Checkbox -->
        <div class="col-span-1 mt-1 flex items-center gap-2 md:col-span-4">
            <input
                type="checkbox"
                id="isUnknownPatient"
                x-model="form.isUnknownPatient"
                class="h-4 w-4 cursor-pointer rounded border-[#d4c5b3] text-[#5c4033] accent-[#5c4033] focus:ring-1 focus:ring-[#8c6d58]"
            />
            <label
                for="isUnknownPatient"
                class="cursor-pointer select-none text-xs font-medium text-[#7a6858]"
            >
                Pasien Tanpa Identitas (Mr. John / Ms. Jane Doe)
            </label>
        </div>
    </div>
</div>