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
            row.role.toLowerCase() == 'admin'
                ? 'border-accent'
                : 'border-blue-500'
        "
    >
        {{ row.role }}
    </Badge>
</template>
