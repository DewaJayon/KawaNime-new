<script setup>
import { Button } from "@/Components/ui/button";
import { Icon } from "@iconify/vue";
import { router, usePage } from "@inertiajs/vue3";

const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const animeslug = page.props.anime.slug;

const edit = (slug) => {
    router.get(route("dashboard.episode.edit", [animeslug, slug]));
};
</script>

<template>
    <div class="flex items-center">
        <Button
            @click="edit(row.slug)"
            variant="ghost"
            size="icon"
            class="hover:bg-transparent whitespace-nowrap"
        >
            <Icon
                icon="material-symbols:edit-outline-rounded"
                class="text-blue-500 text-lg"
            />
        </Button>

        <Button
            variant="ghost"
            size="icon"
            class="hover:bg-transparent whitespace-nowrap"
        >
            <Icon
                icon="material-symbols:delete-outline-rounded"
                class="text-red-500 text-lg"
            />
        </Button>
    </div>
</template>
