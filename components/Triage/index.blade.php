<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#eef2f6]">

    <div 
        x-data="{
            // View & Navigation States
            currentView: 'dashboard',
            viewState: 'list',
            
            // Data States
            patientList: {{ Js::from($patients ?? []) }},
            hospitals: {{ Js::from($locations ?? $hospitals ?? []) }},
            selectedPatient: null,

            // Dashboard Filters & Search Aliases
            search: '',
            searchQuery: '', 
            hospitalSearch: '',
            filterDate: '2026-08-06',

            // Database RS Filters
            searchTerm: '',
            selectedCity: 'ALL',

            // Form State & Dynamic Bindings
            form: {
                bedUpdated: '',
                priority: '',
                kategoriKeluhan: '',
                catatanPerawat: '',
                isDNRS: false,
                foto_triase: null,
                foto_trauma: null
            },
            errors: {},
            isSubmitting: false,

            // Computed: Filtered Hospital Database
            get filteredHospitals() {
                const term = (this.searchTerm || this.hospitalSearch || this.searchQuery || '').toLowerCase();
                return (this.hospitals || []).filter(h => {
                    const textMatch = !term || 
                        (h.nama_lokasi && h.nama_lokasi.toLowerCase().includes(term)) || 
                        (h.alamat && h.alamat.toLowerCase().includes(term));

                    const cityMatch = this.selectedCity === 'ALL' || 
                        (h.alamat && h.alamat.toLowerCase().includes(this.selectedCity.toLowerCase()));

                    return textMatch && cityMatch;
                });
            },

            get filteredDatabaseHospitals() {
                return this.filteredHospitals;
            },

            // Helper Actions
            openDetail(patient) {
                this.selectedPatient = patient;
                this.viewState = 'detail';
            },

            closeDetail() {
                this.selectedPatient = null;
                this.viewState = 'list';
            },

            resetForm() {
                this.form = {
                    bedUpdated: '',
                    priority: '',
                    kategoriKeluhan: '',
                    catatanPerawat: '',
                    isDNRS: false,
                    foto_triase: null,
                    foto_trauma: null
                };
                this.errors = {};
            },

            // Submit Logic
            async submitTriageForm() {
                this.isSubmitting = true;
                this.errors = {};

                const formData = new FormData();
                Object.keys(this.form).forEach(key => {
                    if (this.form[key] !== null && this.form[key] !== undefined) {
                        formData.append(key, this.form[key]);
                    }
                });

                try {
                    const response = await fetch('{{ Route::has('triage.store') ? route('triage.store') : '/triage' }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            this.errors = result.errors || {};
                        } else {
                            alert(result.message || 'Terjadi kesalahan pada sistem.');
                        }
                        return;
                    }

                    if (result.data) {
                        this.patientList.unshift(result.data);
                    }
                    this.resetForm();
                    this.viewState = 'list';

                } catch (error) {
                    console.error('Submission error:', error);
                } finally {
                    this.isSubmitting = false;
                }
            }
        }"
        class="min-h-screen p-5"
    >
        <!-- Card Wrapper -->
        <div class="rounded-lg border border-[#e8decb] bg-white p-4 shadow-xs">
            
            <!-- Top Navigation Bar -->
            <div class="mb-4 flex items-center justify-between border-b-2 border-[#f0e8dd] pb-3">
                <div class="text-base font-bold text-[#4a3525]">
                    Map
                </div>

                <div class="flex gap-2">
                    <button
                        type="button"
                        @click="currentView = 'dashboard'"
                        :class="currentView === 'dashboard' ? 'bg-[#5c4033] text-white' : 'bg-transparent text-[#4a3525] hover:bg-[#f5f0eb]'"
                        class="cursor-pointer rounded border border-[#5c4033] px-3.5 py-1.5 text-xs font-semibold transition-colors"
                    >
                        Dashboard & Peta
                    </button>
                    <button
                        type="button"
                        @click="currentView = 'database'"
                        :class="currentView === 'database' ? 'bg-[#5c4033] text-white' : 'bg-transparent text-[#4a3525] hover:bg-[#f5f0eb]'"
                        class="cursor-pointer rounded border border-[#5c4033] px-3.5 py-1.5 text-xs font-semibold transition-colors"
                    >
                        Database
                    </button>
                </div>
            </div>

            <!-- 1. Database View -->
            <template x-if="currentView === 'database'">
                <x-triage.database-hospital />
            </template>

            <!-- 2. Dashboard View -->
            <div x-show="currentView === 'dashboard'">
                <!-- Detail Mode -->
                <template x-if="viewState === 'detail' && selectedPatient">
                    <x-triage.spgdt.detail 
                        ::patient="selectedPatient" 
                        @back="closeDetail()"
                    />
                </template>

                <!-- Table & Map Partial Mode -->
                <div x-show="viewState !== 'detail'">
                    <x-triage.spgdt.form.table />
                </div>
            </div>

            <!-- 3. Modal View -->
            <div 
                x-show="viewState === 'create_modal'" 
                x-transition.opacity
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40 p-4"
                style="display: none;"
            >
                <div class="max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-lg bg-white shadow-xl">
                    <x-triage.spgdt.form.create 
                        @cancel="viewState = 'list'; resetForm()" 
                        @submit="submitTriageForm()"
                    />
                </div>
            </div>

        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Register spgdtMap for Alpine safely across bundle loading states -->
    <script>
        const initSpgdtMapComponent = () => {
            if (typeof Alpine === 'undefined') return;

            Alpine.data('spgdtMap', (config = {}) => ({
                locations: config.locations || [],
                patients: config.patients || [],
                map: null,

                initMap() {
                    if (this.map) return;

                    const container = this.$el.querySelector('#spgdt-leaflet-map') || this.$el.querySelector('#map') \vert{}\vert{} this.$el;

                    this.map = L.map(container).setView([-6.9175, 107.6191], 12);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(this.map);

                    (this.locations || []).forEach(loc => {
                        const lat = parseFloat(loc.latitude || loc.lat);
                        const lng = parseFloat(loc.longitude || loc.lng || loc.long);

                        if (!isNaN(lat) && !isNaN(lng)) {
                            L.marker([lat, lng])
                                .addTo(this.map)
                                .bindPopup(`
                                    <div class="text-xs font-sans">
                                        <strong class="text-sm">${loc.nama_lokasi || loc.name || 'Rumah Sakit'}</strong><br>
                                        <span>${loc.alamat || loc.location || 'Alamat tidak tersedia'}</span>
                                    </div>
                                `);
                        }
                    });

                    setTimeout(() => {
                        if (this.map) this.map.invalidateSize();
                    }, 300);
                }
            }));
        };

        if (window.Alpine) {
            initSpgdtMapComponent();
        } else {
            document.addEventListener('alpine:init', initSpgdtMapComponent);
        }
    </script>

    <!-- Stack for Child View Scripts (e.g. @push('scripts') inside map component) -->
    @stack('scripts')
    
</body>
</html>