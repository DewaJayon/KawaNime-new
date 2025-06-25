<script setup>
import { AspectRatio } from "@/Components/ui/aspect-ratio";

const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="flex items-center justify-center w-full h-full max-w-[200px] mx-auto"
    >
        <AspectRatio :ratio="16 / 9">
            <img
                :src="`/storage/${row.thumbnail_url}`"
                alt="Image"
                class="rounded-md object-cover w-full h-full"
            />
        </AspectRatio>
    </div>
</template>
