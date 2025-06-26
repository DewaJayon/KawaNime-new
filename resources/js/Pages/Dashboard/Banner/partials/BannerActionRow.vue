<script setup>
import { Button } from "@/Components/ui/button";
import { Icon } from "@iconify/vue";
import Edit from "./Edit.vue";
import { ref } from "vue";
import Spinner from "@/Components/Spinner.vue";
import { router } from "@inertiajs/vue3";
import { toast } from "vue-sonner";
import {
    AlertDialog,
    AlertDialogTrigger,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogCancel,
} from "@/Components/ui/alert-dialog";

const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});

const isDeleting = ref(false);
const dialogRef = ref(false);

const destroy = (id) => {
    isDeleting.value = true;

    router.delete(route("banner.destroy", id), {
        onSuccess: () => {
            toast.success("Banner berhasil dihapus");
        },
        onError: (e) => {
            toast.error("Banner gagal dihapus");
        },
        onFinish: () => {
            isDeleting.value = false;
            dialogRef.value = false;
        },
    });
};
</script>

<template>
    <div class="flex items-center">
        <Edit :row="row" />
        <AlertDialog v-model:open="dialogRef">
            <AlertDialogTrigger as-child>
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
            </AlertDialogTrigger>
            <AlertDialogContent class="sm:max-w-lg bg-gray-800 border-none">
                <AlertDialogHeader>
                    <AlertDialogTitle class="text-white"
                        >Yakin ingin menghapus?</AlertDialogTitle
                    >
                    <AlertDialogDescription class="text-white">
                        Data banner akan dihapus permanen!
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel
                        class="hover:bg-white/70 hover:border-white/10"
                    >
                        Batal
                    </AlertDialogCancel>
                    <Button
                        @click="destroy(row.id)"
                        :disabled="isDeleting"
                        class="bg-red-500 hover:bg-red-600"
                    >
                        <Spinner v-show="isDeleting" />
                        {{ isDeleting ? "Menghapus..." : "Hapus" }}
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
