<script setup>
import { Button } from "@/Components/ui/button";
import { Icon } from "@iconify/vue";
import { router, usePage } from "@inertiajs/vue3";
import { onBeforeUnmount, onMounted, ref } from "vue";
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
import { toast } from "vue-sonner";
import Spinner from "@/Components/Spinner.vue";

const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const dialogRef = ref(false);
const isDeleting = ref(false);

const animeslug = page.props.anime.slug;

const edit = (slug) => {
    router.get(route("dashboard.episode.edit", [animeslug, slug]));
};

const conversionStatus = ref(props.row.conversion_status);
let intervalId = null;

const fetchStatus = async () => {
    try {
        const res = await axios.get(
            route("dashboard.episode.convert", [animeslug, props.row.slug])
        );

        conversionStatus.value = res.data.status;

        if (conversionStatus.value !== "processing" && intervalId) {
            clearInterval(intervalId);
        }
    } catch (error) {
        console.error("Gagal mengambil status:", error);
    }
};

onMounted(() => {
    if (conversionStatus.value === "processing") {
        intervalId = setInterval(fetchStatus, 5000);
    }
});

onBeforeUnmount(() => {
    if (intervalId) clearInterval(intervalId);
});

const destroy = (slug) => {
    isDeleting.value = true;
    router.delete(route("dashboard.episode.destroy", [animeslug, slug]), {
        onSuccess: () => {
            toast.success("Episode berhasil dihapus");
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
        <Button
            v-if="conversionStatus === 'done'"
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
                        Data episode "{{ row.title }}" akan dihapus permanen!
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel
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
    </div>
</template>
