<template>
    <Head title="My Files"/>

    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 mb-3">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li v-for="ans of ancestors.data" :key="ans.id" class="inline-flex items-center">
                    <Link v-if="!ans.parent_id" :href="route('myFiles')"
                          class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <HomeIcon class="w-4 h-4"/>
                        My Files
                    </Link>
                    <div v-else class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor"
                             viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <Link :href="route('myFiles', {folder: ans.path})"
                              class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                            {{ ans.name }}
                        </Link>
                    </div>
                </li>
            </ol>

            <!--            <div class="flex">-->
            <!--                <label class="flex items-center mr-3">-->
            <!--                    Only Favourites-->
            <!--                    <Checkbox @change="showOnlyFavourites" v-model:checked="onlyFavourites" class="ml-2"/>-->
            <!--                </label>-->
            <!--                <ShareFilesButton :all-selected="allSelected" :selected-ids="selectedIds"/>-->
            <!--                <DownloadFilesButton :all="allSelected" :ids="selectedIds" class="mr-2"/>-->
            <!--                <DeleteFilesButton :delete-all="allSelected" :delete-ids="selectedIds" @delete="onDelete"/>-->
            <!--            </div>-->
        </nav>

        <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b sticky top-0">
                <tr>
                    <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        name
                    </th>
                    <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        Owner
                    </th>
                    <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        Last modified
                    </th>
                    <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        Size
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="file of allFiles.data"
                    :key="file.id"
                    @dblclick="openFolder(file)"
                    class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100 cursor-pointer"
                >
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center">
                        <FileIcon :file="file"/>
                        {{ file.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ file.owner }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ file.updated_at }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ file.size }}
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="!allFiles.data.length" class="py-8 text-center sm text-gray-400">
                There is no data in this folder
            </div>

            <div ref="loadMoreIntersect"/>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {onMounted, onUpdated, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router, Link} from '@inertiajs/vue3'
import {HomeIcon} from '@heroicons/vue/20/solid'
import FileIcon from "@/Components/app/FileIcon.vue";
import {httpGet} from "@/Helper/http-helper.js";

const props = defineProps({
    files: Object,
    folder: Object,
    ancestors: Object
})


const loadMoreIntersect = ref();
const allFiles = ref({
    data: props.files.data,
    next: props.files.links.next
});
const openFolder = (file) => {
    if (!file.is_folder) {
        return;
    }

    router.visit(route('myFiles', {folder: file.path}));
}

function loadMore() {
    console.log("load more");
    console.log(allFiles.value.next);

    if (allFiles.value.next === null) {
        return
    }

    httpGet(allFiles.value.next)
        .then(res => {
            console.log('res', res)

            allFiles.value.data = [...allFiles.value.data, ...res.data]
            allFiles.value.next = res.links.next
        })
}

// function onSelectAllChange() {
//     allFiles.value.data.forEach(f => {
//         selected.value[f.id] = allSelected.value
//     })
// }
//
// function toggleFileSelect(file) {
//     selected.value[file.id] = !selected.value[file.id]
//     onSelectCheckboxChange(file)
// }
//
// function onSelectCheckboxChange(file) {
//     if (!selected.value[file.id]) {
//         allSelected.value = false;
//     } else {
//         let checked = true;
//
//         for (let file of allFiles.value.data) {
//             if (!selected.value[file.id]) {
//                 checked = false;
//                 break;
//             }
//         }
//
//         allSelected.value = checked
//
//     }
// }
//
// function onDelete() {
//     allSelected.value = false
//     selected.value = {}
// }
//
// function addRemoveFavourite(file) {
//
//     httpPost(route('file.addToFavourites'), {id: file.id})
//         .then(() => {
//             file.is_favourite = !file.is_favourite
//             showSuccessNotification('Selected files have been added to favourites')
//         })
//         .catch(async (er) => {
//             console.log(er.error.message);
//         })
// }
//
// function showOnlyFavourites() {
//     if (onlyFavourites.value) {
//         params.set('favourites', 1)
//     } else {
//         params.delete('favourites')
//     }
//     router.get(window.location.pathname + '?' + params.toString())
// }

// Hooks
onUpdated(() => {
    allFiles.value = {
        data: props.files.data,
        next: props.files.links.next
    }
})

onMounted(() => {
    // params = new URLSearchParams(window.location.search)
    // onlyFavourites.value = params.get('favourites') === '1'

    // search.value = params.get('search')
    // emitter.on(ON_SEARCH, (value) => {
    //     search.value = value
    // })

    const observer = new IntersectionObserver((entries) =>
            entries.forEach((entry) => entry.isIntersecting && loadMore()
            ),
        {
            rootMargin: "-250px 0px 0px 0px"
        });

    observer.observe(loadMoreIntersect.value)
})
</script>
