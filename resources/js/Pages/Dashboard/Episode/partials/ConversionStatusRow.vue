<script setup>
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import { onBeforeUnmount, onMounted, ref } from "vue";
const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const status = ref(props.row.conversion_status);
let intervalId = null;

const fetchStatus = async () => {
    try {
        const res = await axios.get(
            route("dashboard.episode.convert", [
                page.props.anime.slug,
                props.row.slug,
            ])
        );

        status.value = res.data.status;

        if (status.value !== "processing" && intervalId) {
            clearInterval(intervalId);
        }
    } catch (error) {
        console.error("Gagal mengambil status:", error);
    }
};

const conversionStatusColor = () => {
    switch (status.value) {
        case "processing":
            return "bg-orange-400";
        case "done":
            return "bg-green-400";
        case "failed":
            return "bg-red-400";
        default:
            return "bg-gray-300";
    }
};

onMounted(() => {
    if (status.value === "processing") {
        intervalId = setInterval(fetchStatus, 5000);
    }
});

onBeforeUnmount(() => {
    if (intervalId) clearInterval(intervalId);
});
</script>

<template>
    <div class="flex items-center justify-center w-full h-full">
        <span
            :class="conversionStatusColor(props.row.slug)"
            class="h-2 w-2 rounded-full flex"
        >
            <span
                :class="conversionStatusColor(props.row.slug)"
                class="inline-flex h-2 w-2 animate-ping rounded-full"
            />
        </span>
        <span class="ml-2 text-white capitalize"> {{ status }}</span>
    </div>
</template>
