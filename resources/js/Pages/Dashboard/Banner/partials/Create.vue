<script setup>
import InputError from "@/Components/InputError.vue";
import Spinner from "@/Components/Spinner.vue";
import { Button } from "@/Components/ui/button";
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
import { Icon } from "@iconify/vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const isDialogOpen = ref(false);

const form = useForm({
    name: "",
});

const submit = () => {
    alert("submit");
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger>
            <Button
                variant="outline"
                class="bg-transparent text-white hover:text-black transition-all duration-200 ease-in-out"
            >
                Tambah Banner
                <Icon icon="tabler:plus" width="20" height="20" />
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-lg bg-gray-800 border-none">
            <DialogHeader>
                <DialogTitle class="text-white">Tambah Banner</DialogTitle>
                <DialogDescription class="text-muted-foreground">
                    Tambah banner baru
                </DialogDescription>
            </DialogHeader>
            <form id="form" @submit.prevent="submit">
                <div class="grid gap-4">
                    <div class="items-center gap-4">asd</div>
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
</template>
