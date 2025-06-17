<script setup>
const props = defineProps({
    column: Object, // Hanya column, tidak butuh title lagi
});
</script>

<template>
    <span class="font-medium">
        {{ column.label }}
    </span>
</template>
