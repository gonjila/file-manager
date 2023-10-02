<template>
    <Modal :show="modelValue" @show="onModalShow" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-300">
                Create New Folder
            </h2>

            <div class="mt-6">
                <InputLabel for="folderName" value="Folder Name"/>
                <TextInput id="folderName"
                           ref="folderNameInputRef"
                           type="text"
                           class="mt-1 block w-full"
                           :class="form.errors.name ?  'border-red-500 focus:border-red-500 focus:ring-red-500': ''"
                           placeholder="Folder Name"
                           v-model="form.name"
                           @keyup.enter="createFolder"/>
                <InputError :message="form.errors.name" class="mt-2"/>
            </div>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <PrimaryButton class="ml-3"
                               :class="{'opacity-25' : form.processing}"
                               @click="createFolder"
                               :disabled="form.processing">
                    Submit
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<script setup lang="ts">
import {nextTick, ref} from "vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const {modelValue} = defineProps({
    modelValue: Boolean,
})
const emit = defineEmits(['update:modelValue'])

const folderNameInputRef = ref(null)
const form = useForm({
    name: ''
})

const onModalShow = () => {
    nextTick(() => folderNameInputRef.value && folderNameInputRef.value.focus())
};
const createFolder = () => {
    form.post(route('folder.create'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            form.reset()
        },
        onError: () => folderNameInputRef.value.focus()
    })
}
const closeModal = () => {
    emit('update:modelValue')
    form.clearErrors()
    form.reset()
}


</script>
