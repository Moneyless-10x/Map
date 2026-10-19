<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['patient']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['patient']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div 
    x-data="{
        form: {
            id: '<?php echo e($patient['id'] ?? ''); ?>',
            namaPasien: '<?php echo e($patient['namaPasien'] ?? ''); ?>',
            jenisKelamin: '<?php echo e($patient['jenisKelamin'] ?? ''); ?>',
            nik: '<?php echo e($patient['nik'] ?? ''); ?>',
            tanggalMasuk: '<?php echo e($patient['tanggalMasuk'] ?? ''); ?>',
            kategoriTriase: '<?php echo e($patient['kategoriTriase'] ?? ''); ?>',
            pemeriksaan: {
                jalanNafas: '<?php echo e($patient['pemeriksaan']['jalanNafas'] ?? ''); ?>',
                pernapasan: '<?php echo e($patient['pemeriksaan']['pernapasan'] ?? ''); ?>',
                sirkulasi: '<?php echo e($patient['pemeriksaan']['sirkulasi'] ?? ''); ?>'
            }
        },
        isSubmitting: false,

        setOption(key, opt, category) {
            this.form.pemeriksaan[key] = opt;
            this.form.kategoriTriase = category;
        },

        getBadgeClass() {
            switch(this.form.kategoriTriase) {
                case 'Merah': return 'bg-[#d9534f] text-white';
                case 'Kuning': return 'bg-[#f0ad4e] text-white';
                case 'Hijau': return 'bg-[#5cb85c] text-white';
                default: return 'bg-[#8c6d58] text-white';
            }
        },

        async saveDetail() {
            this.isSubmitting = true;
            try {
                const response = await fetch(`/patients/${this.form.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                if (response.ok) {
                    $dispatch('back');
                }
            } catch (error) {
                console.error('Failed to update triage:', error);
            } finally {
                this.isSubmitting = false;
            }
        }
    }"
    class="overflow-hidden rounded-lg border border-[#e8decb] bg-white shadow-xs"
>
    <!-- Header -->
    <div class="flex items-center gap-3 bg-[#5c4033] px-5 py-3 text-white">
        <button
            type="button"
            @click="$dispatch('back')"
            class="cursor-pointer rounded bg-[#a07855] px-2 py-1 text-white hover:bg-[#8c6d58]"
        >
            ←
        </button>
        <span class="font-bold" x-text="`Edit Triage: ${form.id || '-'}`"></span>
    </div>

    <form @submit.prevent="saveDetail()" class="flex flex-col gap-5 p-5">
        <!-- Section 1: Patient Information -->
        <div>
            <h4 class="mb-2.5 text-sm font-semibold text-[#5c4033]">Informasi Pasien</h4>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4">
                <div>
                    <label class="block text-[11px] text-gray-500">Nama</label>
                    <input 
                        type="text" 
                        x-model="form.namaPasien" 
                        class="w-full rounded border border-[#d4c5b3] bg-[#f5f0eb] p-1.5 text-xs outline-none focus:border-[#5c4033]" 
                    />
                </div>
                <div>
                    <label class="block text-[11px] text-gray-500">Jenis Kelamin</label>
                    <input 
                        type="text" 
                        x-model="form.jenisKelamin" 
                        class="w-full rounded border border-[#d4c5b3] bg-[#f5f0eb] p-1.5 text-xs outline-none focus:border-[#5c4033]" 
                    />
                </div>
                <div>
                    <label class="block text-[11px] text-gray-500">NIK</label>
                    <input 
                        type="text" 
                        x-model="form.nik" 
                        class="w-full rounded border border-[#d4c5b3] bg-[#f5f0eb] p-1.5 text-xs outline-none focus:border-[#5c4033]" 
                    />
                </div>
                <div>
                    <label class="block text-[11px] text-gray-500">Tanggal Masuk</label>
                    <input 
                        type="text" 
                        x-model="form.tanggalMasuk" 
                        class="w-full rounded border border-[#d4c5b3] bg-[#f5f0eb] p-1.5 text-xs outline-none focus:border-[#5c4033]" 
                    />
                </div>
            </div>
        </div>

        <!-- Section 2: Examination Grid -->
        <div>
            <h4 class="mb-2.5 text-sm font-semibold text-[#5c4033]">Pemeriksaan Triase</h4>
            <div class="overflow-x-auto rounded border border-[#d4c5b3]">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr>
                            <th class="border border-[#d4c5b3] bg-[#f5f0eb] p-2 text-[#4a3525]">PEMERIKSAAN</th>
                            <th class="border border-[#d4c5b3] bg-[#d9534f] p-2 text-white">KATEGORI MERAH</th>
                            <th class="border border-[#d4c5b3] bg-[#f0ad4e] p-2 text-white">KATEGORI KUNING</th>
                            <th class="border border-[#d4c5b3] bg-[#5cb85c] p-2 text-white">KATEGORI HIJAU</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Jalan Nafas -->
                        <tr>
                            <td class="border border-[#d4c5b3] bg-[#faf7f2] p-2 font-bold text-[#4a3525]">JALAN NAFAS</td>
                            <td class="border border-[#d4c5b3] p-2">
                                <template x-for="opt in ['Tersumbat', 'Terancam tersumbat']" :key="opt">
                                    <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="jalanNafas" 
                                            :value="opt" 
                                            :checked="form.pemeriksaan.jalanNafas === opt"
                                            @change="setOption('jalanNafas', opt, 'Merah')" 
                                        />
                                        <span x-text="opt"></span>
                                    </label>
                                </template>
                            </td>
                            <td class="border border-[#d4c5b3] p-2">
                                <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="jalanNafas" 
                                        value="Bebas" 
                                        :checked="form.pemeriksaan.jalanNafas === 'Bebas' && form.kategoriTriase === 'Kuning'"
                                        @change="setOption('jalanNafas', 'Bebas', 'Kuning')" 
                                    />
                                    <span>Bebas</span>
                                </label>
                            </td>
                            <td class="border border-[#d4c5b3] p-2">
                                <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="jalanNafas" 
                                        value="Bebas" 
                                        :checked="form.pemeriksaan.jalanNafas === 'Bebas' && form.kategoriTriase === 'Hijau'"
                                        @change="setOption('jalanNafas', 'Bebas', 'Hijau')" 
                                    />
                                    <span>Bebas</span>
                                </label>
                            </td>
                        </tr>

                        <!-- Pernapasan -->
                        <tr>
                            <td class="border border-[#d4c5b3] bg-[#faf7f2] p-2 font-bold text-[#4a3525]">PERNAPASAN</td>
                            <td class="border border-[#d4c5b3] p-2">
                                <template x-for="opt in ['Henti jantung', 'Nadi lemah']" :key="opt">
                                    <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="pernapasan" 
                                            :value="opt" 
                                            :checked="form.pemeriksaan.pernapasan === opt"
                                            @change="setOption('pernapasan', opt, 'Merah')" 
                                        />
                                        <span x-text="opt"></span>
                                    </label>
                                </template>
                            </td>
                            <td class="border border-[#d4c5b3] p-2">
                                <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="pernapasan" 
                                        value="Nadi cepat" 
                                        :checked="form.pemeriksaan.pernapasan === 'Nadi cepat'"
                                        @change="setOption('pernapasan', 'Nadi cepat', 'Kuning')" 
                                    />
                                    <span>Nadi cepat</span>
                                </label>
                            </td>
                            <td class="border border-[#d4c5b3] p-2">
                                <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="pernapasan" 
                                        value="Normal" 
                                        :checked="form.pemeriksaan.pernapasan === 'Normal' && form.kategoriTriase === 'Hijau'"
                                        @change="setOption('pernapasan', 'Normal', 'Hijau')" 
                                    />
                                    <span>Normal</span>
                                </label>
                            </td>
                        </tr>

                        <!-- Sirkulasi -->
                        <tr>
                            <td class="border border-[#d4c5b3] bg-[#faf7f2] p-2 font-bold text-[#4a3525]">SIRKULASI</td>
                            <td class="border border-[#d4c5b3] p-2">
                                <template x-for="opt in ['Henti napas/ gasping', 'Sesak napas berat']" :key="opt">
                                    <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="sirkulasi" 
                                            :value="opt" 
                                            :checked="form.pemeriksaan.sirkulasi === opt"
                                            @change="setOption('sirkulasi', opt, 'Merah')" 
                                        />
                                        <span x-text="opt"></span>
                                    </label>
                                </template>
                            </td>
                            <td class="border border-[#d4c5b3] p-2">
                                <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="sirkulasi" 
                                        value="Sesak napas ringan" 
                                        :checked="form.pemeriksaan.sirkulasi === 'Sesak napas ringan'"
                                        @change="setOption('sirkulasi', 'Sesak napas ringan', 'Kuning')" 
                                    />
                                    <span>Sesak napas ringan</span>
                                </label>
                            </td>
                            <td class="border border-[#d4c5b3] p-2">
                                <label class="flex items-center gap-1.5 py-0.5 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="sirkulasi" 
                                        value="Normal" 
                                        :checked="form.pemeriksaan.sirkulasi === 'Normal' && form.kategoriTriase === 'Hijau'"
                                        @change="setOption('sirkulasi', 'Normal', 'Hijau')" 
                                    />
                                    <span>Normal</span>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 3: Status & Final Action -->
        <div class="flex items-center justify-between border-t border-[#e8decb] pt-3">
            <div>
                <span class="block text-xs font-bold text-[#4a3525]">Kategori Triage Result:</span>
                <span 
                    class="mt-1 inline-block rounded px-3 py-1 text-xs font-bold transition-all"
                    :class="getBadgeClass()"
                    x-text="form.kategoriTriase || 'Belum Ditentukan'"
                ></span>
            </div>
            <button
                type="submit"
                :disabled="isSubmitting"
                class="cursor-pointer rounded bg-[#5c4033] px-6 py-2.5 text-xs font-bold text-white transition-colors hover:bg-[#4a3525] disabled:opacity-50"
            >
                <span x-text="isSubmitting ? 'Menyimpan...' : '💾 Simpan Triage'"></span>
            </button>
        </div>
    </form>
</div><?php /**PATH C:\Users\HP\Desktop\sCReaM\CRM\resources\views/components/triage/spgdt/detail.blade.php ENDPATH**/ ?>