<script setup>
defineProps({
    row: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="flex items-center justify-center w-full h-full">
        <div
            class="w-[80px] h-[120px] rounded overflow-hidden border border-white/10 shadow-sm"
        >
            <img
                loading="lazy"
                :src="`/${row.thumbnail}`"
                alt="Thumbnail"
                class="w-full h-full object-cover"
            />
        </div>
    </div>
</template>
