<div
    x-data="databaseHospitalTable({
        locations: @json($locations ?? $hospitals ?? [])
    })"
    class="rounded-lg border border-[#e8decb] bg-white p-5 shadow-[0_2px_8px_rgba(92,64,51,0.08)]"
>
    <!-- Header & Stats Bar -->
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-[#f0e8dd] pb-4">
        <div>
            <h3 class="m-0 text-lg font-bold text-[#4a3525]">
                Database Tempat & Lokasi
            </h3>
            <p class="mt-1 text-xs text-[#8c6d58]">
                Kelola dan filter seluruh daftar lokasi yang terdaftar berdasarkan wilayah.
            </p>
        </div>

        <div class="flex gap-2">
            <div class="rounded-md border border-[#e8decb] bg-[#f5f0eb] px-3.5 py-1.5 text-center">
                <span class="text-[10px] uppercase text-[#8c6d58]">
                    Menampilkan
                </span>
                <div class="text-base font-bold text-[#5c4033]">
                    <span x-text="filteredDatabaseHospitals.length"></span> / <span x-text="(hospitals || []).length"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Control Bar -->
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2.5">
        <div class="flex flex-1 flex-wrap gap-2.5 max-w-[750px]">
            <select
                x-model="selectedCity"
                class="cursor-pointer rounded border border-[#d4c5b3] bg-white px-3 py-2 text-xs font-semibold text-[#4a3525] outline-none"
            >
                <option value="ALL">📍 Semua Wilayah</option>
                <option value="Jakarta">Jakarta</option>
                <option value="Bandung">Bandung</option>
                <option value="Cimahi">Cimahi</option>
                <option value="Bogor">Bogor</option>
                <option value="Surabaya">Surabaya</option>
            </select>

            <!-- Category Filter Dropdown -->
            <select
                x-model="selectedCategory"
                @change="selectedTier = 'ALL'"
                class="cursor-pointer rounded border border-[#d4c5b3] bg-white px-3 py-2 text-xs font-semibold text-[#4a3525] outline-none"
            >
                <option value="ALL">🏥 Semua Kategori</option>
                <option value="Sarana Kesehatan">Sarana Kesehatan</option>
                <option value="Sarana Perekonomian">Sarana Perekonomian</option>
                <option value="Sarana Pendidikan">Sarana Pendidikan</option>
            </select>

            <!-- Dynamic Tier Dropdown based on Selected Category -->
            <select
                x-model="selectedTier"
                class="cursor-pointer rounded border border-[#d4c5b3] bg-white px-3 py-2 text-xs font-semibold text-[#4a3525] outline-none"
            >
                <option value="ALL">🏷️ Semua Tier / Tipe</option>
                
                <!-- Health Tiers -->
                <template x-if="selectedCategory === 'ALL' || selectedCategory === 'Sarana Kesehatan'">
                    <optgroup label="Sarana Kesehatan">
                        <option value="RS_A">RS Tipe A</option>
                        <option value="RS_B">RS Tipe B</option>
                        <option value="RS_C">RS Tipe C</option>
                        <option value="Puskesmas">Puskesmas</option>
                    </optgroup>
                </template>

                <!-- Business Tiers -->
                <template x-if="selectedCategory === 'ALL' || selectedCategory === 'Sarana Perekonomian'">
                    <optgroup label="Sarana Perekonomian">
                        <option value="PT">PT (Perusahaan)</option>
                        <option value="SV">SV (Survei)</option>
                    </optgroup>
                </template>

                <!-- Education Tiers -->
                <template x-if="selectedCategory === 'ALL' || selectedCategory === 'Sarana Pendidikan'">
                    <optgroup label="Sarana Pendidikan">
                        <option value="PG">PG (Playgroup)</option>
                        <option value="TK">TK</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="SMK">SMK</option>
                        <option value="UNI">Universitas</option>
                    </optgroup>
                </template>
            </select>

            <input
                type="text"
                x-model="searchTerm"
                placeholder="Cari nama tempat atau alamat..."
                class="min-w-[160px] flex-1 rounded border border-[#d4c5b3] px-3 py-2 text-xs outline-none focus:border-[#5c4033]"
            />

            <button
                type="button"
                x-show="searchTerm || selectedCity !== 'ALL' || selectedCategory !== 'ALL' || selectedTier !== 'ALL'"
                @click="searchTerm = ''; selectedCity = 'ALL'; selectedCategory = 'ALL'; selectedTier = 'ALL';"
                class="cursor-pointer rounded border border-[#d4c5b3] bg-[#f5f0eb] px-3 py-2 text-xs text-[#4a3525] hover:bg-[#e8decb]"
            >
                Reset Filter
            </button>
        </div>

        <button
            type="button"
            onclick="window.location.reload()"
            class="cursor-pointer rounded border border-[#d4c5b3] bg-[#f5f0eb] px-3.5 py-2 text-xs font-semibold text-[#5c4033] hover:bg-[#e8decb]"
        >
            🔄 Refresh
        </button>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto rounded-md border border-[#f0e8dd]">
        <table class="w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b-2 border-[#e8decb] bg-[#f5f0eb] text-[#4a3525]">
                    <th class="w-[50px] p-3">No</th>
                    <th class="p-3">Nama Tempat</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Tier / Tipe</th>
                    <th class="p-3">Lokasi / Alamat</th>
                    <th class="p-3">Koordinat (Lat, Long)</th>
                    <th class="w-[130px] p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-if="filteredDatabaseHospitals.length === 0">
                    <tr>
                        <td colSpan="7" class="p-6 text-center text-gray-400">
                            Tidak ada lokasi yang ditemukan.
                        </td>
                    </tr>
                </template>

                <template x-for="(row, index) in filteredDatabaseHospitals" :key="row.id || index">
                    <tr
                        class="border-b border-[#f0e8dd]"
                        :class="index % 2 === 0 ? 'bg-white' : 'bg-[#fcfaf7]'"
                    >
                        <td class="p-3 text-[#8c6d58]" x-text="index + 1"></td>
                        <td class="p-3 font-semibold text-[#5c4033]" x-text="row.nama_lokasi || row.name || '-'"></td>
                        <td class="p-3 text-[#4a3525]">
                            <span class="rounded bg-[#f5f0eb] px-2 py-0.5 font-medium text-[#5c4033]" x-text="row.kategori || 'Sarana Kesehatan'"></span>
                        </td>
                        <td class="p-3 text-[#4a3525]">
                            <span class="rounded bg-[#e8decb]/50 px-2 py-0.5 font-mono text-[11px] text-[#5c4033]" x-text="row.tier || '-'"></span>
                        </td>
                        <td class="p-3 text-[#4a3525]" x-text="row.alamat || row.location || '-'"></td>
                        <td class="p-3">
                            <span class="rounded bg-[#e8decb] px-2 py-1 font-mono text-[11px] text-[#4a3525]">
                                <span x-text="(row.latitude || row.lat) && (row.longitude || row.lng) ? `${parseFloat(row.latitude || row.lat).toFixed(4)}, ${parseFloat(row.longitude || row.lng).toFixed(4)}` : 'N/A'"></span>
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex justify-center gap-1.5">
                                <button
                                    type="button"
                                    @click="openEditModal(row)"
                                    class="cursor-pointer rounded bg-[#5c4033] px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-[#4a3525]"
                                >
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    @click="deleteLocation(row.id)"
                                    class="cursor-pointer rounded bg-[#d9534f] px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-[#c9302c]"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Edit Location Modal -->
    <div
        x-show="editModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div class="w-full max-w-md rounded-lg bg-white p-5 shadow-lg border border-[#e8decb]">
            <h4 class="mb-4 text-sm font-bold text-[#4a3525]">Edit Data Lokasi</h4>
            <form @submit.prevent="updateLocation()" class="space-y-3">
                <div>
                    <label class="block text-[11px] font-medium text-[#4a3525]">Nama Tempat</label>
                    <input 
                        type="text" 
                        x-model="editingItem.nama_lokasi" 
                        class="mt-1 w-full rounded border border-[#d4c5b3] p-2 text-xs outline-none focus:border-[#5c4033]" 
                        required 
                    />
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-[#4a3525]">Kategori</label>
                    <select 
                        x-model="editingItem.kategori" 
                        class="mt-1 w-full rounded border border-[#d4c5b3] p-2 text-xs outline-none focus:border-[#5c4033]"
                    >
                        <option value="Sarana Kesehatan">Sarana Kesehatan</option>
                        <option value="Sarana Perekonomian">Sarana Perekonomian</option>
                        <option value="Sarana Pendidikan">Sarana Pendidikan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-[#4a3525]">Tier / Tipe</label>
                    <input 
                        type="text" 
                        x-model="editingItem.tier" 
                        placeholder="Contoh: RS_A, Puskesmas, PT, SD, dll"
                        class="mt-1 w-full rounded border border-[#d4c5b3] p-2 text-xs outline-none focus:border-[#5c4033]" 
                    />
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-[#4a3525]">Alamat</label>
                    <input 
                        type="text" 
                        x-model="editingItem.alamat" 
                        class="mt-1 w-full rounded border border-[#d4c5b3] p-2 text-xs outline-none focus:border-[#5c4033]" 
                    />
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] font-medium text-[#4a3525]">Latitude</label>
                        <input 
                            type="text" 
                            x-model="editingItem.latitude" 
                            class="mt-1 w-full rounded border border-[#d4c5b3] p-2 text-xs outline-none" 
                        />
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-[#4a3525]">Longitude</label>
                        <input 
                            type="text" 
                            x-model="editingItem.longitude" 
                            class="mt-1 w-full rounded border border-[#d4c5b3] p-2 text-xs outline-none" 
                        />
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button 
                        type="button" 
                        @click="editModalOpen = false" 
                        class="rounded border border-[#d4c5b3] bg-[#f5f0eb] px-3 py-1.5 text-xs text-[#4a3525]"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        :disabled="isUpdating" 
                        class="rounded bg-[#5c4033] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#4a3525]"
                    >
                        <span x-text="isUpdating ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('databaseHospitalTable', (config = {}) => ({
            searchTerm: '',
            selectedCity: 'ALL',
            selectedCategory: 'ALL',
            selectedTier: 'ALL',
            hospitals: config.locations || [],
            editModalOpen: false,
            editingItem: { id: null, nama_lokasi: '', kategori: '', tier: '', alamat: '', latitude: '', longitude: '' },
            isUpdating: false,

            init() {
                window.addEventListener('location-added', (e) => {
                    if (e.detail && !this.hospitals.some(h => h.id == e.detail.id)) {
                        this.hospitals.unshift(e.detail);
                    }
                });

                window.addEventListener('location-deleted', (e) => {
                    if (e.detail && e.detail.id) {
                        this.hospitals = this.hospitals.filter(h => h.id != e.detail.id);
                    }
                });

                window.addEventListener('location-updated', (e) => {
                    if (e.detail && e.detail.id) {
                        const idx = this.hospitals.findIndex(h => h.id == e.detail.id);
                        if (idx !== -1) {
                            this.hospitals[idx] = { ...this.hospitals[idx], ...e.detail };
                        }
                    }
                });
            },

            get filteredDatabaseHospitals() {
                const query = (this.searchTerm || '').toLowerCase().trim();
                const city = this.selectedCity || 'ALL';
                const catFilter = this.selectedCategory || 'ALL';
                const tierFilter = this.selectedTier || 'ALL';

                return (this.hospitals || []).filter(item => {
                    const name = (item.nama_lokasi || item.name || '').toLowerCase();
                    const address = (item.alamat || item.location || '').toLowerCase();
                    const category = (item.kategori || '').toLowerCase();
                    const tier = (item.tier || item.tipe || '').toLowerCase();
                    
                    const matchesQuery = !query || name.includes(query) || address.includes(query);
                    const matchesCity = city === 'ALL' || address.includes(city.toLowerCase());
                    const matchesCategory = catFilter === 'ALL' || category.includes(catFilter.toLowerCase());
                    const matchesTier = tierFilter === 'ALL' || tier.includes(tierFilter.toLowerCase());

                    return matchesQuery && matchesCity && matchesCategory && matchesTier;
                });
            },

            openEditModal(item) {
                this.editingItem = {
                    id: item.id,
                    nama_lokasi: item.nama_lokasi || item.name || '',
                    kategori: item.kategori || 'Sarana Kesehatan',
                    tier: item.tier || '',
                    alamat: item.alamat || item.location || '',
                    latitude: item.latitude || item.lat || '',
                    longitude: item.longitude || item.lng || ''
                };
                this.editModalOpen = true;
            },

            async updateLocation() {
                this.isUpdating = true;
                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                    const response = await fetch(`/api/triage-locations/${this.editingItem.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(this.editingItem)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        const idx = this.hospitals.findIndex(h => h.id == this.editingItem.id);
                        if (idx !== -1) {
                            this.hospitals[idx] = { ...this.hospitals[idx], ...this.editingItem };
                        }
                        window.dispatchEvent(new CustomEvent('location-updated', { detail: this.editingItem }));
                        this.editModalOpen = false;
                        alert('Lokasi berhasil diperbarui!');
                    } else {
                        alert(result.message || 'Gagal memperbarui lokasi.');
                    }
                } catch (err) {
                    console.error('Error updating location:', err);
                    alert('Terjadi kesalahan koneksi.');
                } finally {
                    this.isUpdating = false;
                }
            },

            async deleteLocation(id) {
                if (!confirm('Apakah Anda yakin ingin menghapus lokasi ini?')) return;

                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                    const response = await fetch(`/api/triage-locations/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    });

                    if (response.ok) {
                        this.hospitals = this.hospitals.filter(h => h.id != id);
                        window.dispatchEvent(new CustomEvent('location-deleted', { detail: { id } }));
                        alert('Lokasi berhasil dihapus!');
                    } else {
                        alert('Gagal menghapus lokasi dari database.');
                    }
                } catch (err) {
                    console.error('Error deleting location:', err);
                    alert('Terjadi kesalahan koneksi.');
                }
            }
        }));
    });
</script>