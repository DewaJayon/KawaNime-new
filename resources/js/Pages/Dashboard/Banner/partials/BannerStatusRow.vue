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
        :class="row.is_active == 1 ? 'border-accent' : 'border-red-500'"
    >
        {{ row.is_active == 1 ? "Aktif" : "Tidak Aktif" }}
    </Badge>
</template>
