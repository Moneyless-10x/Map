<div 
    x-data="{
        form: {
            tanggalMasuk: new Date().toISOString().split('T')[0],
            jamMasuk: new Date().toTimeString().slice(0, 5),
            jenisKelamin: '',
            namaPasien: '',
            nik: '',
            kategoriPasien: 'Dewasa',
            kategoriKeluhan: 'Trauma'
        },
        errors: {},
        isSubmitting: false,

        async submitForm() {
            this.isSubmitting = true;
            this.errors = {};

            try {
                const response = await fetch('/patients', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                if (response.ok) {
                    const result = await response.json();
                    $dispatch('submit-success', result);
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    this.errors = errorData.errors || {};
                }
            } catch (error) {
                console.error('Failed to create triage:', error);
            } finally {
                this.isSubmitting = false;
            }
        }
    }"
    class="w-[750px] max-w-[90vw] overflow-hidden rounded-lg border border-[#d4c5b3] bg-white shadow-xl"
>
    <!-- Header -->
    <div class="flex items-center justify-between bg-[#5c4033] px-5 py-3 text-white">
        <h4 class="m-0 text-base font-bold text-white">
            Create New Triage
        </h4>
        <button
            type="button"
            @click="$dispatch('cancel')"
            class="cursor-pointer border-none bg-transparent text-xl text-white transition-opacity hover:opacity-80"
        >
            ✕
        </button>
    </div>

    <!-- Form Body -->
    <form @submit.prevent="submitForm()" class="flex flex-col gap-4.5 bg-[#faf8f5] p-6">
        <!-- Row 1: Tanggal & Jam -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-[#333333]">
                    Tanggal Masuk
                </label>
                <input
                    type="date"
                    x-model="form.tanggalMasuk"
                    class="w-full rounded border border-[#d4c5b3] bg-[#f0ebe5] px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
                />
                <template x-if="errors.tanggalMasuk">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.tanggalMasuk[0]"></span>
                </template>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-[#333333]">
                    Jam Masuk
                </label>
                <input
                    type="time"
                    x-model="form.jamMasuk"
                    class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
                />
                <template x-if="errors.jamMasuk">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.jamMasuk[0]"></span>
                </template>
            </div>
        </div>

        <!-- Row 2: Gender, Nama, NIK -->
        <div class="grid grid-cols-[1fr_1.5fr_1fr] gap-4">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-[#333333]">
                    Jenis Kelamin<span class="text-red-500">*</span>
                </label>
                <select
                    x-model="form.jenisKelamin"
                    class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
                >
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
                <template x-if="errors.jenisKelamin">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.jenisKelamin[0]"></span>
                </template>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-[#333333]">
                    Nama Pasien<span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    x-model="form.namaPasien"
                    placeholder="Masukkan nama"
                    class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
                />
                <template x-if="errors.namaPasien">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.namaPasien[0]"></span>
                </template>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-[#333333]">
                    NIK
                </label>
                <input
                    type="text"
                    x-model="form.nik"
                    placeholder="16 digit NIK"
                    class="w-full rounded border border-[#d4c5b3] bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-[#8c6d58]"
                />
                <template x-if="errors.nik">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.nik[0]"></span>
                </template>
            </div>
        </div>

        <!-- Row 3: Segmented Kategori Pasien & Radio Keluhan -->
        <div class="grid grid-cols-[1fr_1.2fr] items-center gap-5">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-[#333333]">
                    Kategori Pasien
                </label>
                <div class="flex overflow-hidden rounded border border-[#8c6d58]">
                    <template x-for="cat in ['Dewasa', 'Anak', 'Bayi']" :key="cat">
                        <button
                            type="button"
                            @click="form.kategoriPasien = cat"
                            :class="form.kategoriPasien === cat ? 'bg-[#8c6d58] font-bold text-white' : 'bg-[#f5f0eb] text-[#5c4033]'"
                            class="flex-1 cursor-pointer border-none py-2 px-1 text-xs transition-colors"
                            x-text="cat"
                        ></button>
                    </template>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-[#333333]">
                    Kategori Keluhan<span class="text-red-500">*</span>
                </label>
                <div class="flex flex-wrap gap-2.5">
                    <template x-for="k in ['Trauma', 'Nyeri', 'Psikologi', 'Obsteri', 'Lain-Lain']" :key="k">
                        <label class="flex cursor-pointer items-center gap-1 text-xs text-[#333333]">
                            <input
                                type="radio"
                                name="kategoriKeluhan"
                                :value="k"
                                x-model="form.kategoriKeluhan"
                                class="accent-[#5c4033]"
                            />
                            <span x-text="k"></span>
                        </label>
                    </template>
                </div>
                <template x-if="errors.kategoriKeluhan">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.kategoriKeluhan[0]"></span>
                </template>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="mt-2.5 flex justify-end gap-2.5 border-t border-[#d4c5b3] pt-3">
            <button
                type="button"
                @click="$dispatch('cancel')"
                class="cursor-pointer rounded border border-[#d4c5b3] bg-white px-5 py-2 text-xs font-semibold text-[#333333] transition-colors hover:bg-[#f0ebe5]"
            >
                Batal
            </button>
            <button
                type="submit"
                :disabled="isSubmitting"
                class="cursor-pointer rounded border-none bg-[#5c4033] px-6 py-2 text-xs font-bold text-white transition-colors hover:bg-[#4a3525] disabled:opacity-50"
            >
                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan'"></span>
            </button>
        </div>
    </form>
</div>