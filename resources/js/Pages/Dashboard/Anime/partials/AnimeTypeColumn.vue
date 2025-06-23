<script setup>
import { Badge } from "@/Components/ui/badge";

const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});

const typeBorderClass = {
    series: "border-indigo-600",
    movie: "border-red-600",
    live_action: "border-yellow-600",
};
</script>

<template>
    <Badge
        variant="outline"
        class="capitalize text-white"
        :class="typeBorderClass[row.type.toLowerCase()]"
    >
        {{ row.type.replace("_", " ") }}
    </Badge>
</template>
