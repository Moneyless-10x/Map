<div
    x-data="spgdtMap({
        locations: <?php echo json_encode($locations ?? $hospitals ?? [], 15, 512) ?>,
        patients: <?php echo json_encode($patients ?? [], 15, 512) ?>
    })"
    class="relative mt-3 overflow-hidden rounded-lg border border-[#e8decb] shadow-xs"
>
    <!-- Status Bar -->
    <div class="flex items-center justify-between bg-[#f5f0eb] px-3 py-2 text-xs text-[#4a3525]">
        <span class="font-medium">
            Total lokasi terdaftar: <strong x-text="filteredHospitals.length"></strong>
        </span>
        <template x-if="searchQuery">
            <span class="text-[11px] italic text-[#8c6d58]">
                Menampilkan hasil untuk: "<span x-text="searchQuery"></span>"
            </span>
        </template>
    </div>

    <!-- Map Container Wrapper -->
    <div class="relative w-full">
        <!-- Leaflet Map Container -->
        <div x-ref="mapContainer" class="h-[450px] w-full bg-[#fcfaf7] z-0"></div>

        <!-- Floating Right-Side Category Sidebar -->
        <div class="absolute right-3 top-3 z-[1000] w-64 flex flex-col gap-1.5 max-h-[90%] overflow-y-auto p-2 bg-white/90 backdrop-blur-md rounded-lg shadow-lg border border-[#e8decb]">
            
            <div class="px-2 py-1 text-xs font-bold text-[#4a3525] border-b border-[#f0e8dd] mb-1">
                Kategori Peta
            </div>

            <!-- 1. Health Section (Sarana Kesehatan - Red Theme) -->
            <div class="flex flex-col rounded overflow-hidden shadow-xs border border-[#fca5a5]"
                 :class="activeSection === 'health' ? 'bg-[#dc2626] text-white' : 'bg-[#fee2e2] text-[#991b1b]'">
                <button 
                    type="button"
                    @click="toggleCategory('health')"
                    class="flex items-center justify-between px-3 py-2 text-xs font-semibold transition-colors cursor-pointer"
                >
                    <span class="flex items-center gap-2">🏥 Sarana Kesehatan</span>
                    <span class="text-xs" x-text="openCategory === 'health' ? '▲' : '▼'"></span>
                </button>

                <div x-show="openCategory === 'health'" x-cloak class="bg-white text-[#1e293b] flex flex-col text-xs border-t border-[#fca5a5]">
                    
                    <!-- Rumah Sakit Master Checkbox -->
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-[#fef2f2] border-b border-[#f1f5f9] font-semibold text-[#991b1b] cursor-pointer">
                        <input type="checkbox" @change="toggleRumahSakit($event)" :checked="isRumahSakitSelected" class="rounded text-[#dc2626] focus:ring-[#dc2626]" />
                        <span>• Rumah Sakit</span>
                    </label>

                    <!-- Indented Tiers -->
                    <label class="flex items-center gap-2 pl-6 pr-3 py-1.5 hover:bg-[#fef2f2] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="RS_A" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#dc2626] focus:ring-[#dc2626]" />
                        <span>• Tipe A (RS Umum Pusat)</span>
                    </label>
                    <label class="flex items-center gap-2 pl-6 pr-3 py-1.5 hover:bg-[#fef2f2] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="RS_B" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#dc2626] focus:ring-[#dc2626]" />
                        <span>• Tipe B (RS Umum Provinsi)</span>
                    </label>
                    <label class="flex items-center gap-2 pl-6 pr-3 py-1.5 hover:bg-[#fef2f2] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="RS_C" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#dc2626] focus:ring-[#dc2626]" />
                        <span>• Tipe C (RS Umum Kab/Kota)</span>
                    </label>

                    <!-- Puskesmas -->
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-[#fef2f2] font-semibold text-[#991b1b] cursor-pointer">
                        <input type="checkbox" value="Puskesmas" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#dc2626] focus:ring-[#dc2626]" />
                        <span>🚑 Puskesmas</span>
                    </label>
                </div>
            </div>

            <!-- 2. Business Section (Sarana Perekonomian - Green Theme) -->
            <div class="flex flex-col rounded overflow-hidden shadow-xs border border-[#86efac]"
                 :class="activeSection === 'business' ? 'bg-[#16a34a] text-white' : 'bg-[#dcfce7] text-[#166534]'">
                <button 
                    type="button"
                    @click="toggleCategory('business')"
                    class="flex items-center justify-between px-3 py-2 text-xs font-semibold transition-colors cursor-pointer"
                >
                    <span class="flex items-center gap-2">🏢 Sarana Perekonomian</span>
                    <span class="text-xs" x-text="openCategory === 'business' ? '▲' : '▼'"></span>
                </button>

                <div x-show="openCategory === 'business'" x-cloak class="bg-white text-[#1e293b] flex flex-col text-xs border-t border-[#86efac]">
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-[#f0fdf4] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="PT" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#16a34a] focus:ring-[#16a34a]" />
                        <span>🏢 PT (Perusahaan)</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-[#f0fdf4] cursor-pointer">
                        <input type="checkbox" value="SV" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#16a34a] focus:ring-[#16a34a]" />
                        <span>📈 SV (Survei/Sektor Lain)</span>
                    </label>
                </div>
            </div>

            <!-- 3. Education Section (Sarana Pendidikan - Blue Theme) -->
            <div class="flex flex-col rounded overflow-hidden shadow-xs border border-[#93c5fd]"
                 :class="activeSection === 'education' ? 'bg-[#2563eb] text-white' : 'bg-[#eff6ff] text-[#1e40af]'">
                <button 
                    type="button"
                    @click="toggleCategory('education')"
                    class="flex items-center justify-between px-3 py-2 text-xs font-semibold transition-colors cursor-pointer"
                >
                    <span class="flex items-center gap-2">🎓 Sarana Pendidikan</span>
                    <span class="text-xs" x-text="openCategory === 'education' ? '▲' : '▼'"></span>
                </button>

                <div x-show="openCategory === 'education'" x-cloak class="bg-white text-[#1e293b] flex flex-col text-xs border-t border-[#93c5fd]">
                    <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-[#eff6ff] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="PG" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#2563eb] focus:ring-[#2563eb]" />
                        <span>• PG (Playgroup)</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-[#eff6ff] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="TK" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#2563eb] focus:ring-[#2563eb]" />
                        <span>• TK (Taman Kanak-kanak)</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-[#eff6ff] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="SD" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#2563eb] focus:ring-[#2563eb]" />
                        <span>• SD (Sekolah Dasar)</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-[#eff6ff] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="SMP" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#2563eb] focus:ring-[#2563eb]" />
                        <span>• SMP (Sekolah Menengah Pertama)</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-[#eff6ff] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="SMA" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#2563eb] focus:ring-[#2563eb]" />
                        <span>• SMA (Sekolah Menengah Atas)</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-[#eff6ff] border-b border-[#f1f5f9] cursor-pointer">
                        <input type="checkbox" value="SMK" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#2563eb] focus:ring-[#2563eb]" />
                        <span>• SMK (Sekolah Kejuruan)</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-[#eff6ff] cursor-pointer">
                        <input type="checkbox" value="UNI" x-model="selectedFilters" @change="renderMarkers()" class="rounded text-[#2563eb] focus:ring-[#2563eb]" />
                        <span>• Universitas / Tinggi</span>
                    </label>
                </div>
            </div>

            <!-- Reset Filter Button -->
            <button 
                type="button"
                @click="resetMapFilter()"
                class="mt-1 rounded border border-[#d4c5b3] bg-[#f5f0eb] px-3 py-1.5 text-xs font-semibold text-[#5c4033] hover:bg-[#e8decb] cursor-pointer text-center transition-colors"
            >
                🔄 Reset Filter Peta
            </button>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('spgdtMap', (config = {}) => ({
            map: null,
            markersGroup: null,
            hospitals: config.locations || [],
            patients: config.patients || [],
            searchQuery: '',
            
            openCategory: null,
            activeSection: null,
            selectedFilters: [],

            init() {
                this.$nextTick(() => {
                    if (!this.map && this.$refs.mapContainer) {
                        this.map = L.map(this.$refs.mapContainer).setView([-6.9175, 107.6191], 13);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors',
                            maxZoom: 19,
                        }).addTo(this.map);

                        this.markersGroup = L.layerGroup().addTo(this.map);

                        this.map.on('click', (e) => this.handleMapClick(e));
                    }

                    setTimeout(() => {
                        if (this.map) {
                            this.map.invalidateSize();
                            this.renderMarkers();
                        }
                    }, 200);
                });

                if (this.$data.hospitalSearch !== undefined) {
                    this.$watch('$data.hospitalSearch', (val) => {
                        this.searchQuery = val || '';
                        this.renderMarkers();
                    });
                }

                window.addEventListener('location-deleted', (e) => {
                    const deletedId = e.detail.id;
                    this.hospitals = this.hospitals.filter(h => h.id != deletedId);
                    this.renderMarkers();
                });

                window.addEventListener('location-updated', (e) => {
                    const idx = this.hospitals.findIndex(h => h.id == e.detail.id);
                    if (idx !== -1) {
                        this.hospitals[idx] = { ...this.hospitals[idx], ...e.detail };
                        this.renderMarkers();
                    }
                });

                window.addEventListener('location-added', (e) => {
                    if (e.detail && !this.hospitals.some(h => h.id == e.detail.id)) {
                        this.hospitals.push(e.detail);
                        this.renderMarkers();
                    }
                });
            },

            toggleCategory(section) {
                this.openCategory = this.openCategory === section ? null : section;
                this.activeSection = this.openCategory ? section : null;
            },

            resetMapFilter() {
                this.openCategory = null;
                this.activeSection = null;
                this.selectedFilters = [];
                this.renderMarkers();
            },

            get isRumahSakitSelected() {
                return ['RS_A', 'RS_B', 'RS_C'].every(tier => this.selectedFilters.includes(tier));
            },

            toggleRumahSakit(event) {
                const checked = event.target.checked;
                const rsTiers = ['RS_A', 'RS_B', 'RS_C'];
                
                if (checked) {
                    // Add RS tiers if not already selected
                    rsTiers.forEach(tier => {
                        if (!this.selectedFilters.includes(tier)) {
                            this.selectedFilters.push(tier);
                        }
                    });
                } else {
                    // Remove RS tiers
                    this.selectedFilters = this.selectedFilters.filter(tier => !rsTiers.includes(tier));
                }
                this.renderMarkers();
            },

            get filteredHospitals() {
                const query = (this.searchQuery || '').toLowerCase().trim();

                return this.hospitals.filter(hospital => {
                    const name = (hospital.nama_lokasi || hospital.name || '').toLowerCase();
                    const address = (hospital.alamat || hospital.location || '').toLowerCase();
                    const category = (hospital.kategori || '').toLowerCase();
                    const tier = (hospital.tier || hospital.tipe || '').toLowerCase();

                    const matchesQuery = !query || name.includes(query) || address.includes(query);
                    
                    let matchesCheckboxes = true;
                    if (this.selectedFilters.length > 0) {
                        matchesCheckboxes = this.selectedFilters.some(filter => {
                            const f = filter.toLowerCase();
                            return category.includes(f) || tier.includes(f);
                        });
                    }

                    return matchesQuery && matchesCheckboxes;
                });
            },

            getCustomIcon(category = '', tier = '') {
                const cat = (category || '').toLowerCase();
                const t = (tier || '').toLowerCase();

                let logoUrl = '/img/icon/Rumah_sakit.png';
                let borderColor = '#dc2626'; // Red for Health

                if (cat.includes('perekonomian') || cat.includes('pt') || cat.includes('sv') || t.includes('pt') || t.includes('sv')) {
                    logoUrl = '/img/icon/PT.png';
                    borderColor = '#16a34a'; // Green for Business
                } else if (cat.includes('pendidikan') || ['pg', 'tk', 'sd', 'smp', 'sma', 'smk', 'uni'].some(edu => t.includes(edu))) {
                    logoUrl = '/img/icon/Education.png';
                    borderColor = '#2563eb'; // Blue for Education
                } else if (cat.includes('puskesmas') || t.includes('puskesmas')) {
                    logoUrl = '/img/icon/Puskesmas.png';
                    borderColor = '#dc2626';
                }

                return L.divIcon({
                    className: 'custom-logo-pin',
                    html: `
                        <div style="
                            position: relative;
                            width: 38px;
                            height: 38px;
                            background: #ffffff;
                            border: 2.5px solid ${borderColor};
                            border-radius: 50% 50% 50% 0;
                            transform: rotate(-45deg);
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <img 
                                src="${logoUrl}" 
                                style="
                                    width: 22px;
                                    height: 22px;
                                    object-fit: contain;
                                    transform: rotate(45deg);
                                " 
                                alt="pin logo"
                            />
                        </div>
                    `,
                    iconSize: [38, 38],
                    iconAnchor: [19, 38],
                    popupAnchor: [0, -38]
                });
            },

            renderMarkers() {
                if (!this.map) return;

                if (!this.markersGroup) {
                    this.markersGroup = L.layerGroup().addTo(this.map);
                } else {
                    this.markersGroup.clearLayers();
                }

                this.filteredHospitals.forEach((hospital) => {
                    const lat = parseFloat(hospital.latitude || hospital.lat);
                    const lng = parseFloat(hospital.longitude || hospital.lng || hospital.long);

                    if (isNaN(lat) || isNaN(lng)) return;

                    const name = hospital.nama_lokasi || hospital.name || 'Lokasi';
                    const address = hospital.alamat || hospital.location || 'Alamat tidak tersedia';
                    const category = hospital.kategori || 'Sarana Kesehatan';
                    const tier = hospital.tier || hospital.tipe || '-';

                    const customIcon = this.getCustomIcon(category, tier);
                    const marker = L.marker([lat, lng], { icon: customIcon, riseOnHover: true });

                    const popupHtml = `
                        <div style="font-family: inherit; font-size: 12px; color: #1e293b; min-width: 180px;">
                            <h5 style="margin: 0 0 4px 0; font-size: 13px; font-weight: bold; color: #0f172a;">${name}</h5>
                            <p style="margin: 0 0 4px 0; font-size: 11px; color: #64748b;">${address}</p>
                            <div style="font-size: 10px; color: #475569; margin-bottom: 6px;">
                                <strong>Kategori:</strong> ${category}<br/>
                                <strong>Tier/Tipe:</strong> ${tier}<br/>
                                <strong>Lat:</strong> ${lat.toFixed(5)}, <strong>Lng:</strong> ${lng.toFixed(5)}
                            </div>
                            <span style="display: inline-block; padding: 2px 6px; background-color: #f1f5f9; color: #334155; font-size: 10px; border-radius: 4px; font-weight: bold;">
                                Terdaftar di Database
                            </span>
                        </div>
                    `;

                    marker.bindPopup(popupHtml);
                    this.markersGroup.addLayer(marker);
                });
            },

            async handleMapClick(e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);

                const loadingPopup = L.popup()
                    .setLatLng(e.latlng)
                    .setContent('<div style="font-size:11px; color:#334155;">Mencari info lokasi...</div>')
                    .openOn(this.map);

                let placeName = 'Lokasi Terpilih';
                let placeAddress = 'Alamat tidak ditemukan';

                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
                    if (response.ok) {
                        const data = await response.json();
                        placeAddress = data.display_name || placeAddress;
                        placeName = data.name || (data.address ? (data.address.hospital || data.address.amenity || data.address.road) : 'Lokasi Terpilih') || placeName;
                    }
                } catch (err) {
                    console.warn('Geocoding fetch failed:', err);
                }

                const popupContainer = document.createElement('div');
                popupContainer.style.cssText = 'font-family: inherit; font-size: 12px; color: #1e293b; min-width: 220px;';
                popupContainer.innerHTML = `
                    <h5 style="margin: 0 0 4px 0; font-size: 13px; font-weight: bold; color: #0f172a;">${placeName}</h5>
                    <p style="margin: 0 0 6px 0; font-size: 11px; color: #64748b; line-height: 1.3;">${placeAddress}</p>
                    
                    <label style="font-size: 10px; font-weight: bold; color: #334155;">Kategori Utama:</label>
                    <select id="pin-category-select" style="width: 100%; font-size: 11px; margin-bottom: 6px; padding: 4px; border-radius: 4px; border: 1px solid #cbd5e1; outline: none;">
                        <option value="Sarana Kesehatan">🏥 Sarana Kesehatan</option>
                        <option value="Sarana Perekonomian">🏢 Sarana Perekonomian</option>
                        <option value="Sarana Pendidikan">🎓 Sarana Pendidikan</option>
                    </select>

                    <div id="health-tier-wrapper" style="margin-bottom: 8px;">
                        <label style="font-size: 10px; font-weight: bold; color: #334155;">Sub-Kategori Kesehatan:</label>
                        <select id="pin-tier-select" style="width: 100%; font-size: 11px; padding: 4px; border-radius: 4px; border: 1px solid #cbd5e1; outline: none;">
                            <option value="RS_A">Tipe A (RS Umum Pusat)</option>
                            <option value="RS_B">Tipe B (RS Umum Provinsi)</option>
                            <option value="RS_C">Tipe C (RS Umum Kab/Kota)</option>
                            <option value="Puskesmas">Puskesmas</option>
                        </select>
                    </div>

                    <div id="biz-tier-wrapper" style="margin-bottom: 8px; display: none;">
                        <label style="font-size: 10px; font-weight: bold; color: #334155;">Sub-Kategori Bisnis:</label>
                        <select id="pin-biz-select" style="width: 100%; font-size: 11px; padding: 4px; border-radius: 4px; border: 1px solid #cbd5e1; outline: none;">
                            <option value="PT">PT (Perusahaan)</option>
                            <option value="SV">SV (Survei/Sektor Lain)</option>
                        </select>
                    </div>

                    <div id="edu-tier-wrapper" style="margin-bottom: 8px; display: none;">
                        <label style="font-size: 10px; font-weight: bold; color: #334155;">Sub-Kategori Pendidikan:</label>
                        <select id="pin-edu-select" style="width: 100%; font-size: 11px; padding: 4px; border-radius: 4px; border: 1px solid #cbd5e1; outline: none;">
                            <option value="PG">PG (Playgroup)</option>
                            <option value="TK">TK (Taman Kanak-kanak)</option>
                            <option value="SD">SD (Sekolah Dasar)</option>
                            <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
                            <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                            <option value="SMK">SMK (Sekolah Kejuruan)</option>
                            <option value="UNI">Universitas / Tinggi</option>
                        </select>
                    </div>

                    <button id="btn-add-db" style="width: 100%; cursor: pointer; background-color: #0f172a; color: white; border: none; padding: 6px 10px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                        + Tambahkan ke Database
                    </button>
                `;

                const catSelect = popupContainer.querySelector('#pin-category-select');
                const healthWrapper = popupContainer.querySelector('#health-tier-wrapper');
                const bizWrapper = popupContainer.querySelector('#biz-tier-wrapper');
                const eduWrapper = popupContainer.querySelector('#edu-tier-wrapper');
                
                catSelect.addEventListener('change', (ev) => {
                    healthWrapper.style.display = 'none';
                    bizWrapper.style.display = 'none';
                    eduWrapper.style.display = 'none';

                    if (ev.target.value === 'Sarana Kesehatan') {
                        healthWrapper.style.display = 'block';
                    } else if (ev.target.value === 'Sarana Perekonomian') {
                        bizWrapper.style.display = 'block';
                    } else if (ev.target.value === 'Sarana Pendidikan') {
                        eduWrapper.style.display = 'block';
                    }
                });

                popupContainer.querySelector('#btn-add-db').addEventListener('click', (ev) => {
                    const selectedCategory = catSelect.value;
                    let selectedTier = 'RS_A';
                    if (selectedCategory === 'Sarana Kesehatan') {
                        selectedTier = popupContainer.querySelector('#pin-tier-select').value;
                    } else if (selectedCategory === 'Sarana Perekonomian') {
                        selectedTier = popupContainer.querySelector('#pin-biz-select').value;
                    } else if (selectedCategory === 'Sarana Pendidikan') {
                        selectedTier = popupContainer.querySelector('#pin-edu-select').value;
                    }
                        
                    this.saveLocationToDb(ev.currentTarget, placeName, placeAddress, lat, lng, selectedCategory, selectedTier);
                });

                loadingPopup.setContent(popupContainer);
            },

            async saveLocationToDb(buttonEl, name, address, lat, lng, category = 'Sarana Kesehatan', tier = 'RS_A') {
                buttonEl.disabled = true;
                buttonEl.innerText = 'Menyimpan...';

                const payload = {
                    nama_lokasi: name !== 'Lokasi Terpilih' ? name : 'Lokasi Baru',
                    kategori: category,
                    tier: tier,
                    alamat: address,
                    latitude: parseFloat(lat),
                    longitude: parseFloat(lng)
                };

                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>';
                    
                    const response = await fetch('/api/triage-locations', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        alert('Berhasil disimpan ke database!');
                        const newItem = result.data;
                        
                        this.searchQuery = '';
                        this.hospitals.push(newItem);
                        this.renderMarkers();
                        this.map.closePopup();

                        const newLat = parseFloat(newItem.latitude);
                        const newLng = parseFloat(newItem.longitude);
                        if (!isNaN(newLat) && !isNaN(newLng)) {
                            this.map.flyTo([newLat, newLng], 15, { animate: true, duration: 1 });
                        }

                        window.dispatchEvent(new CustomEvent('location-added', { detail: newItem }));
                    } else {
                        alert(result.message || 'Gagal menyimpan ke database.');
                        buttonEl.disabled = false;
                        buttonEl.innerText = '+ Tambahkan ke Database';
                    }
                } catch (err) {
                    console.error('Error saving location:', err);
                    alert('Terjadi kesalahan koneksi.');
                    buttonEl.disabled = false;
                    buttonEl.innerText = '+ Tambahkan ke Database';
                }
            }
        }));
    });
</script><?php /**PATH C:\Users\HP\Desktop\sCReaM\CRM\resources\views/components/triage/spgdt/form/map.blade.php ENDPATH**/ ?>