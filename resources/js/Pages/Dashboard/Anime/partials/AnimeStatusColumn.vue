<script setup>
import { Badge } from "@/Components/ui/badge";
const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Badge
        variant="outline"
        class="capitalize text-white"
        :class="
            row.status.toLowerCase() == 'completed'
                ? 'border-accent'
                : 'border-blue-500'
        "
    >
        {{ row.status }}
    </Badge>
</template>
