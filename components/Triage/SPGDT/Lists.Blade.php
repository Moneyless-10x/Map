@props(['patients' => []])

<div 
    x-data="{
        searchKey: '',
        currentPage: 1,
        pageSize: 10,
        
        get filteredData() {
            if (!this.searchKey.trim()) return {{ Js::from($patients) }};
            const query = this.searchKey.toLowerCase();
            return {{ Js::from($patients) }}.filter(p => 
                (p.namaPasien && p.namaPasien.toLowerCase().includes(query)) ||
                (p.rekamMedis && p.rekamMedis.toLowerCase().includes(query)) ||
                (p.noTriage && p.noTriage.toLowerCase().includes(query))
            );
        },

        get paginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredData.slice(start, start + this.pageSize);
        },

        get totalPages() {
            return Math.ceil(this.filteredData.length / this.pageSize) || 1;
        }
    }"
    class="rounded-lg border border-[#e8decb] bg-white shadow-xs"
>
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#f0e8dd] bg-white p-4">
        <h5 class="text-base font-bold text-[#4a3525]">
            Riwayat Data Pasien SPGDT
        </h5>
        <input
            type="text"
            x-model="searchKey"
            @input="currentPage = 1"
            placeholder="Cari pasien..."
            class="w-full max-w-[220px] rounded border border-[#d4c5b3] px-3 py-1.5 text-xs text-[#4a3525] outline-none focus:border-[#5c4033]"
        />
    </div>

    <!-- Table Body -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b border-[#e8decb] bg-[#fdfbf7] text-[#4a3525]">
                    <th class="p-3 font-bold">Tanggal & Jam</th>
                    <th class="p-3 font-bold">Nama Pasien</th>
                    <th class="p-3 font-bold">No. RM</th>
                    <th class="p-3 font-bold">Prioritas</th>
                    <th class="p-3 text-center font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e8dd]">
                <template x-for="(patient, index) in paginatedData" :key="patient.id || index">
                    <tr class="hover:bg-[#faf7f2] transition-colors">
                        <td class="p-3 text-[#4a3525]">
                            <span x-text="`${patient.tanggalMasuk || '-'} ${patient.waktuKedatangan || ''}`"></span>
                        </td>
                        <td class="p-3 font-bold text-[#4a3525]">
                            <span x-text="patient.namaPasien || '-'"></span>
                        </td>
                        <td class="p-3 text-[#4a3525]">
                            <span x-text="patient.rekamMedis || '-'"></span>
                        </td>
                        <td class="p-3">
                            <span 
                                class="inline-block rounded bg-[#2b6cb0] px-2 py-0.5 text-[11px] font-semibold text-white"
                                x-text="patient.priority || 'P2'"
                            ></span>
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex justify-center gap-1.5">
                                <button
                                    type="button"
                                    @click="$dispatch('select-detail', patient)"
                                    class="cursor-pointer rounded border border-[#2b6cb0] px-2 py-0.5 text-[11px] font-medium text-[#2b6cb0] transition-colors hover:bg-[#2b6cb0] hover:text-white"
                                >
                                    Detail
                                </button>
                                <button
                                    type="button"
                                    @click="$dispatch('select-edit', patient)"
                                    class="cursor-pointer rounded border border-[#d97706] px-2 py-0.5 text-[11px] font-medium text-[#d97706] transition-colors hover:bg-[#d97706] hover:text-white"
                                >
                                    Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                <template x-if="filteredData.length === 0">
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            Tidak ada data pasien ditemukan.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="flex items-center justify-between border-t border-[#f0e8dd] p-3 text-xs text-[#4a3525]">
        <div>
            Menampilkan <span x-text="filteredData.length ? ((currentPage - 1) * pageSize) + 1 : 0"></span> - 
            <span x-text="Math.min(currentPage * pageSize, filteredData.length)"></span> dari 
            <span x-text="filteredData.length"></span> data
        </div>
        <div class="flex gap-1">
            <button
                type="button"
                :disabled="currentPage === 1"
                @click="currentPage--"
                class="rounded border border-[#d4c5b3] px-2.5 py-1 text-xs disabled:opacity-40"
            >
                Prev
            </button>
            <button
                type="button"
                :disabled="currentPage >= totalPages"
                @click="currentPage++"
                class="rounded border border-[#d4c5b3] px-2.5 py-1 text-xs disabled:opacity-40"
            >
                Next
            </button>
        </div>
    </div>
</div>