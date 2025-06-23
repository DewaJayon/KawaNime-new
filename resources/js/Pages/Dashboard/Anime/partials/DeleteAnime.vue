<script setup>
import Spinner from "@/Components/Spinner.vue";
import {
    AlertDialog,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogCancel,
} from "@/Components/ui/alert-dialog";
import { Button } from "@/Components/ui/button";
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { toast } from "vue-sonner";

const props = defineProps({
    row: Object,
    open: Boolean,
});

const emit = defineEmits(["update:open"]);

const closeDialog = () => {
    emit("update:open", false);
};

const dialogOpen = computed({
    get: () => props.open,
    set: (value) => emit("update:open", value),
});

const isDeleting = ref(false);

const destroy = (slug) => {
    isDeleting.value = true;
    router.delete(route("dashboard.anime.destroy", slug), {
        onSuccess: () => {
            toast.success("Anime berhasil dihapus");
        },
        onError: (e) => {
            toast.error("Anime gagal dihapus");
        },
        onFinish: () => {
            isDeleting.value = false;
            closeDialog();
        },
    });
};
</script>

<template>
    <AlertDialog v-model:open="dialogOpen">
        <AlertDialogContent class="sm:max-w-lg bg-gray-800 border-none">
            <AlertDialogHeader>
                <AlertDialogTitle class="text-white"
                    >Yakin ingin menghapus?</AlertDialogTitle
                >
                <AlertDialogDescription class="text-white">
                    Data anime "{{ row.title }}" akan dihapus permanen!
                    Besertanya semua data yang berkaitan dengan anime ini.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel
                    @click="closeDialog"
                    class="hover:bg-white/70 hover:border-white/10"
                >
                    Batal
                </AlertDialogCancel>
                <Button
                    @click="destroy(row.slug)"
                    :disabled="isDeleting"
                    class="bg-red-500 hover:bg-red-600"
                >
                    <Spinner v-show="isDeleting" />
                    {{ isDeleting ? "Menghapus..." : "Hapus" }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
