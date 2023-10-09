<script setup>
import Navigation from "@/Components/app/Navigation.vue";
import SearchForm from "@/Components/app/SearchForm.vue";
import UserSettingsDropdown from "@/Components/app/UserSettingsDropdown.vue";
import {onMounted, ref} from "vue";
import {emitter, FILE_UPLOAD_STARTED} from "@/event-bus.js";
import {useForm, usePage} from "@inertiajs/vue3";

const dragOver = ref(false)

const page = usePage();
const fileUploadForm = useForm({
    files: [],
    relative_paths: [],
    parent_id: null,
})

onMounted(() => {
    emitter.on(FILE_UPLOAD_STARTED, uploadFiles)
})
const uploadFiles = (files) => {
    console.log('files', files)

    fileUploadForm.parent_id = page.props.folder.id;
    fileUploadForm.files = files;
    fileUploadForm.relative_paths = [...files].map((f) => f.webkitRelativePath);

    fileUploadForm.post(route('file.store'));
}

const handleDrop = (ev) => {
    dragOver.value = false;
    const files = ev.dataTransfer.files

    if (!files.length) {
        return
    }

    uploadFiles(files)
}
const onDragOver = () => {
    dragOver.value = true;
}
const onDragLeave = () => {
    dragOver.value = false;
}
</script>

<template>
    <div @drop.prevent="handleDrop"
         @dragover.prevent="onDragOver"
         @dragleave.prevent="onDragLeave"
         class="h-screen bg-gray-50 flex w-full gap-4"
    >

        <div v-if="dragOver"
             class="text-gray-500 text-center color-[#8d8d8d] h-screen w-screen py-8 border-2 border-dashed border-[gray] m-2 flex items-center justify-center"
        >
            Drop files here to upload
        </div>

        <template v-else>
            <Navigation/>

            <main class="flex flex-col flex-1 px-4 overflow-hidden">
                <div class="flex items-center justify-between w-full">
                    <SearchForm/>
                    <UserSettingsDropdown/>
                </div>

                <div class="flex-1 flex flex-col overflow-hidden">
                    <slot/>
                </div>
            </main>
        </template>
    </div>
</template>
