<template>
    <Menu as="div" class="relative inline-block text-left">
        <MenuButton
            class="flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font -semibold text-gpay-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:ba-gray-50"
        >
            Create New
        </MenuButton>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems
                class="absolute left-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
                <div class="px-1 py-1">
                    <MenuItem as="button" @click.prevent="showCreateFolderModal" v-slot="{ active }">
                        <p class="text-gray-700 block px-4 py-2 text-sm">New Folder</p>
                    </MenuItem>
                </div>

                <div class="px-1 py-1">
                    <MenuItem as="button" v-slot="{ active }">
                        <p class="text-gray-700 block px-4 py-2 text-sm">Upload Files</p>
                    </MenuItem>
                    <MenuItem as="button" v-slot="{ active }">
                        <p class="text-gray-700 block px-4 py-2 text-sm">Upload Folder</p>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>

    <CreateFolderModal v-model="isShownCreateFolderModal"/>
</template>

<script setup lang="ts">
import {ref} from 'vue'
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import CreateFolderModal from "@/Components/app/CreateFolderModal.vue";

const isShownCreateFolderModal = ref(false)

const showCreateFolderModal = () => {
    isShownCreateFolderModal.value = true
}
</script>
