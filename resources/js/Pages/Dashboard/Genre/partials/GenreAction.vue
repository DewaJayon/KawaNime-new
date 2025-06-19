<script setup>
import { Icon } from "@iconify/vue";
import { router, useForm } from "@inertiajs/vue3";
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
    AlertDialogAction,
} from "@/Components/ui/alert-dialog";
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import Spinner from "@/Components/Spinner.vue";
import { Button } from "@/Components/ui/button";

const props = defineProps({
    row: Object,
});

const form = useForm({
    name: props.row.name,
});

const isDialogOpen = ref(false);

const edit = (slug) => {
    form.put(route("genre.update", slug), {
        onSuccess: () => {
            toast.success("Genre berhasil diubah");
            isDialogOpen.value = false;
        },
        onError: (e) => {
            toast.error("Genre gagal diubah");
        },
        onFinish: () => {
            form.reset();
        },
    });
};

const isDeleting = ref(false);

const destroy = (slug) => {
    isDeleting.value = true;
    router.delete(route("genre.destroy", slug), {
        onSuccess: () => {
            toast.success("Genre berhasil dihapus");
        },
        onError: (e) => {
            toast.error("Genre gagal dihapus");
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <div class="flex gap-2 items-center justify-center">
        <Dialog v-model:open="isDialogOpen">
            <DialogTrigger as-child>
                <Button
                    variant="ghost"
                    class="hover:bg-transparent whitespace-nowrap"
                >
                    <Icon
                        icon="material-symbols:edit-outline-rounded"
                        class="text-blue-500 text-lg"
                    />
                </Button>
            </DialogTrigger>
            <DialogContent class="sm:max-w-lg bg-gray-800 border-none">
                <DialogHeader>
                    <DialogTitle class="text-white">Edit Genre</DialogTitle>
                    <DialogDescription class="text-muted-foreground">
                        Edit genre "{{ row.name }}"
                    </DialogDescription>
                </DialogHeader>
                <form id="form" @submit.prevent="edit(row.slug)">
                    <div class="grid gap-4">
                        <div class="items-center gap-4">
                            <Label for="genre" class="text-right text-white">
                                Genre
                            </Label>
                            <Input
                                placeholder="Genre"
                                autocomplete="off"
                                v-model="form.name"
                                id="genre"
                                type="text"
                                class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                            />
                            <InputError :message="form.errors.name" />
                        </div>
                    </div>
                </form>
                <DialogFooter>
                    <DialogClose>
                        <Button
                            variant="ghost"
                            class="mr-2 text-white hover:text-slate-700 transition-all duration-500 ease-in-out"
                            >Batal</Button
                        >
                    </DialogClose>
                    <Button
                        :disabled="form.processing"
                        class="disabled:opacity-50"
                        type="submit"
                        form="form"
                    >
                        <Spinner v-show="form.processing" />
                        {{ form.processing ? "Menyimpan..." : "Simpan" }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <AlertDialog>
            <AlertDialogTrigger as-child>
                <Button
                    variant="ghost"
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
                    <AlertDialogDescription>
                        Data genre "{{ row.name }}" akan dihapus permanen.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel> Batal </AlertDialogCancel>
                    <AlertDialogAction
                        @click="destroy(row.slug)"
                        :disabled="isDeleting"
                    >
                        <Spinner v-show="form.processing" />
                        {{
                            isDeleting ? "Menghapus..." : "Hapus"
                        }}</AlertDialogAction
                    >
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
