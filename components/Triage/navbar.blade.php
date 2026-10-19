<nav class="mb-4 flex items-center justify-between border-b border-[#e8decb] bg-white px-6 py-3 shadow-[0_2px_4px_rgba(92,64,51,0.05)]">
    <!-- Brand / Identifier -->
    <div class="flex items-center gap-2.5">
        <div class="rounded bg-[#5c4033] px-2 py-1 text-xs font-bold text-white">
            Map
        </div>
        <span class="text-lg font-bold text-[#4a3525]">
            Map
        </span>
    </div>

    <!-- Local View Switcher -->
    <div class="flex gap-2">
        <button
            type="button"
            @click="currentView = 'dashboard'"
            :class="currentView === 'dashboard' ? 'bg-[#5c4033] text-white' : 'bg-transparent text-[#4a3525] hover:bg-[#5c4033]/10'"
            class="cursor-pointer rounded border border-[#5c4033] px-4 py-1.5 text-xs font-semibold transition-colors"
        >
            Dashboard & Map
        </button>

        <button
            type="button"
            @click="currentView = 'database'"
            :class="currentView === 'database' ? 'bg-[#5c4033] text-white' : 'bg-transparent text-[#4a3525] hover:bg-[#5c4033]/10'"
            class="cursor-pointer rounded border border-[#5c4033] px-4 py-1.5 text-xs font-semibold transition-colors"
        >
            Database
        </button>
    </div>
</nav>