<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    modelValue: [File, String, null], // Support File dari input & string dari DB
});
const emit = defineEmits(["update:modelValue"]);

const previewUrl = ref(null);

// Perbarui preview saat thumbnail berubah
watch(
    () => props.modelValue,
    (newVal) => {
        if (newVal instanceof File) {
            previewUrl.value = URL.createObjectURL(newVal);
        } else if (typeof newVal === "string" && newVal !== "") {
            previewUrl.value = newVal.startsWith("/") ? newVal : "/" + newVal;
        } else {
            previewUrl.value = null;
        }
    },
    { immediate: true }
);

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file && file.type.startsWith("image/")) {
        emit("update:modelValue", file);
    }
};

const onDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith("image/")) {
        emit("update:modelValue", file);
    }
};

const onDragOver = (e) => {
    e.preventDefault();
};
</script>

<template>
    <div
        @drop="onDrop"
        @dragover="onDragOver"
        class="relative flex flex-col items-center justify-center border-2 border-dashed border-gray-500 hover:border-white transition-all duration-300 rounded-lg p-4 cursor-pointer bg-gray-900 text-white"
        :class="{ 'p-1': previewUrl, 'p-12': !previewUrl }"
    >
        <!-- File input transparan -->
        <input
            type="file"
            accept="image/*"
            class="absolute inset-0 opacity-0 cursor-pointer"
            @change="onFileChange"
        />

        <!-- PREVIEW -->
        <template v-if="previewUrl">
            <img
                :src="previewUrl"
                alt="Thumbnail Preview"
                class="max-h-64 w-full object-contain rounded"
            />
            <p class="mt-2 text-sm text-gray-400">Klik untuk ganti gambar</p>
        </template>

        <!-- EMPTY STATE -->
        <template v-else>
            <div class="flex flex-col items-center gap-2 text-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-10 w-10 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    />
                </svg>
                <p class="text-sm font-medium">Drag & Drop Gambar</p>
                <p class="text-xs text-gray-400">
                    Atau klik untuk memilih gambar dari perangkat
                </p>
            </div>
        </template>
    </div>
</template>
