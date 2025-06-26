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
    <div class="flex items-center justify-center w-full h-full mx-auto">
        <AspectRatio :ratio="16 / 9">
            <img
                :src="`/${row.image}`"
                alt="Image"
                class="rounded-md object-cover w-full h-full"
            />
        </AspectRatio>
    </div>
</template>
