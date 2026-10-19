@props(['patient'])

<div 
    x-data="{
        form: {{ Js::from($patient) }},
        errors: {},
        isSubmitting: false,

        async submitForm() {
            this.isSubmitting = true;
            this.errors = {};

            try {
                const response = await fetch(`/patients/${this.form.id}`, {
                    method: 'PUT',
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
                console.error('Error updating form:', error);
            } finally {
                this.isSubmitting = false;
            }
        }
    }"
    class="mb-4 rounded-lg border border-[#e8decb] bg-white shadow-xs"
>
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-[#e8decb] bg-[#f5f0eb] px-4 py-3">
        <h5 class="m-0 text-base font-bold text-[#4a3525]">
            Edit Form Intake SPGDT
        </h5>
        <span class="rounded-full bg-[#e6c594] px-2.5 py-0.5 text-xs font-semibold text-[#4a3525]">
            Modus Edit Pasien
        </span>
    </div>

    <!-- Form Body -->
    <div class="p-4">
        <form @submit.prevent="submitForm()" class="flex flex-col gap-4">
            <!-- Form Sections as Included Blade Partials / Components -->
            <x-triage.form.first-input />
            <x-triage.form.priority-classification />
            <x-triage.form.triage-category />
            <x-triage.form.details-triage-nurse />
            <x-triage.form.updated-bed />
            <x-triage.form.dnrs />

            <!-- Action Buttons -->
            <div class="flex justify-end gap-2 border-t border-[#f0e8dd] pt-4">
                <button
                    type="button"
                    @click="$dispatch('cancel')"
                    class="cursor-pointer rounded border border-[#d4c5b3] bg-[#f5f0eb] px-4 py-2 text-xs font-semibold text-[#4a3525] transition-colors hover:bg-[#e8decb]"
                >
                    Batal Edit
                </button>
                <button
                    type="submit"
                    :disabled="isSubmitting"
                    class="cursor-pointer rounded bg-[#5c4033] px-4 py-2 text-xs font-semibold text-white transition-colors hover:bg-[#4a3525] disabled:opacity-50"
                >
                    <span x-text="isSubmitting ? 'Updating...' : 'Update Data Pasien'"></span>
                </button>
            </div>
        </form>
    </div>
</div>