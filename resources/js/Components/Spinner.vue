<script setup>
import { Icon } from "@iconify/vue";

const props = defineProps({
    class: { type: null, required: false },
});
</script>

<template>
    <Icon
        icon="line-md:loading-loop"
        width="24"
        height="24"
        :class="props.class"
    />
</template>
