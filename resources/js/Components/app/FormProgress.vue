<template>
    <div v-if="form.progress"
         class="w-[240px] p-2 bg-white border-gray-900 border shadow-lg rounded flex flex-col absolute right-8 bottom-4">
        <div class="text-xs mb-2">Uploading {{ form.files.length }} files</div>
        <div class="h-3 bg-gray-100 rounded-md">
            <div class="h-full bg-gray-900 rounded-md transition-all"
                 :style="{width: form.progress.percentage+'%'}">

            </div>
        </div>
    </div>
</template>

<script setup>
// Imports

// Uses

// Refs

// Props & Emit
const props = defineProps({
    form: Object
})

// Computed

// Methods

// Hooks

</script>
