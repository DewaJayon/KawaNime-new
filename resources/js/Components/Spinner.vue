<script setup>
import { Loader2 } from "lucide-vue-next";

const props = defineProps({
    class: { type: null, required: false },
});
</script>

<template>
    <Loader2 class="w-5 h-5 animate-spin" :class="props.class" />
</template>
