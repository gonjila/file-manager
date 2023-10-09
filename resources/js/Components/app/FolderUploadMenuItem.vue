<script setup>
import {MenuItem} from "@headlessui/vue";
import {emitter, FILE_UPLOAD_STARTED} from "@/event-bus.js";

const onInputChange = (ev) => {
    emitter.emit(FILE_UPLOAD_STARTED, ev.target.files)
}
</script>

<template>
    <MenuItem as="button" v-slot="{ active }">
        <p class="text-gray-700 block px-4 py-2 text-sm relative">
            Upload Folder
            <input type="file"
                   @change.prevent="onInputChange"
                   multiple
                   directory
                   webkitdirectory
                   class="absolute top-0 left-0 right-0 bottom-0 cursor-pointer opacity-0"
            >
        </p>
    </MenuItem>
</template>
