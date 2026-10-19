<div
    x-data="{
        form: {
            name: '',
            location: '',
            latitude: '',
            longitude: ''
        },
        errors: {},
        processing: false,

        async submitForm() {
            this.processing = true;
            this.errors = {};

            try {
                const response = await fetch('/hospitals', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                if (response.ok) {
                    const result = await response.json();
                    
                    // Push newly added hospital directly into map & table reactivity state
                    if (Array.isArray(hospitals)) {
                        hospitals.push(result.hospital || this.form);
                    }

                    // Reset Form Data
                    this.form = { name: '', location: '', latitude: '', longitude: '' };
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    this.errors = errorData.errors || {};
                }
            } catch (err) {
                console.error('Failed to save hospital:', err);
            } finally {
                this.processing = false;
            }
        }
    }"
    class="mt-5 rounded-md border border-[#e8decb] bg-[#fcfaf7] p-4"
>
    <h4 class="mb-3 text-sm font-semibold text-[#4a3525]">
        + Tambah lokasi Baru
    </h4>

    <form @submit.prevent="submitForm()" class="grid gap-2.5">
        <div class="flex flex-wrap gap-2.5">
            <div class="flex-2 min-w-[200px]">
                <input
                    type="text"
                    x-model="form.name"
                    placeholder="Nama lokasi"
                    class="w-full rounded border border-[#d4c5b3] bg-white px-2.5 py-2 text-xs outline-none focus:border-[#5c4033]"
                />
                <template x-if="errors.name">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.name[0]"></span>
                </template>
            </div>

            <div class="flex-2 min-w-[200px]">
                <input
                    type="text"
                    x-model="form.location"
                    placeholder="Lokasi / Alamat"
                    class="w-full rounded border border-[#d4c5b3] bg-white px-2.5 py-2 text-xs outline-none focus:border-[#5c4033]"
                />
                <template x-if="errors.location">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.location[0]"></span>
                </template>
            </div>
        </div>

        <div class="flex flex-wrap gap-2.5">
            <div class="flex-1 min-w-[140px]">
                <input
                    type="number"
                    step="any"
                    x-model="form.latitude"
                    placeholder="Latitude (cth: -6.9175)"
                    class="w-full rounded border border-[#d4c5b3] bg-white px-2.5 py-2 text-xs outline-none focus:border-[#5c4033]"
                />
                <template x-if="errors.latitude">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.latitude[0]"></span>
                </template>
            </div>

            <div class="flex-1 min-w-[140px]">
                <input
                    type="number"
                    step="any"
                    x-model="form.longitude"
                    placeholder="Longitude (cth: 107.6191)"
                    class="w-full rounded border border-[#d4c5b3] bg-white px-2.5 py-2 text-xs outline-none focus:border-[#5c4033]"
                />
                <template x-if="errors.longitude">
                    <span class="mt-1 block text-[11px] text-red-500" x-text="errors.longitude[0]"></span>
                </template>
            </div>

            <button
                type="submit"
                :disabled="processing"
                :class="processing ? 'cursor-not-allowed bg-[#8c6d58]' : 'cursor-pointer bg-[#2b6cb0] hover:bg-[#2b6cb0]/90'"
                class="rounded px-4 py-2 text-xs font-semibold text-white transition-colors"
            >
                <span x-text="processing ? 'Menyimpan...' : 'Simpan Rumah Sakit'"></span>
            </button>
        </div>
    </form>
</div><?php /**PATH C:\Users\HP\Desktop\sCReaM\CRM\resources\views/components/triage/add-hospital.blade.php ENDPATH**/ ?>