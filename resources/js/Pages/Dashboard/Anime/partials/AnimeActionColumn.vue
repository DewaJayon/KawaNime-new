<script setup>
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Icon } from "@iconify/vue";
import AnimeUpdateForm from "../form/AnimeUpdateForm.vue";
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import DeleteAnime from "./DeleteAnime.vue";

const props = defineProps({
    row: Object,
});

const isEditDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const page = usePage();
const genres = page.props.genres;

const goToEpisode = (slug) => {
    router.get(route("dashboard.episode.index", slug));
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger class="hover:bg-zinc-600 rounded">
            <Icon icon="tabler:dots" width="24" height="24" />
        </DropdownMenuTrigger>

        <DropdownMenuContent class="bg-slate-800 text-white">
            <DropdownMenuItem
                class="hover:text-black cursor-pointer"
                @click="isEditDialogOpen = true"
            >
                <Icon icon="tabler:edit" />
                Edit
            </DropdownMenuItem>

            <DropdownMenuItem
                class="hover:text-black cursor-pointer"
                @click="goToEpisode(row.slug)"
            >
                <Icon icon="tabler:plus" />
                Tambah Episode
            </DropdownMenuItem>

            <DropdownMenuItem
                @click="isDeleteDialogOpen = true"
                class="hover:bg-red-500 cursor-pointer focus:bg-red-500"
            >
                <Icon
                    icon="material-symbols:delete-outline-rounded"
                    class="text-lg"
                />
                Hapus
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <!-- Dialog dipanggil di luar dropdown -->
    <AnimeUpdateForm
        :row="row"
        :genres="genres"
        v-model:open="isEditDialogOpen"
    />

    <DeleteAnime :row="row" v-model:open="isDeleteDialogOpen" />
</template>
