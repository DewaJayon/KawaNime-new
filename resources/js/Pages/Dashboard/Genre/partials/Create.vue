<script setup>
import { Button } from "@/components/ui/button";
import { Icon } from "@iconify/vue";
import Spinner from "@/Components/Spinner.vue";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
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
import { ref } from "vue";
import { toast } from "vue-sonner";

const page = usePage();

const isDialogOpen = ref(false);

const form = useForm({
    name: "",
});

const submit = () => {
    form.post(route("genre.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success("Genre berhasil ditambahkan", {
                description: `Oleh ${page.props.auth.user.name}`,
            });
            isDialogOpen.value = false;
        },
    });
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger>
            <Button
                variant="outline"
                class="bg-transparent text-white hover:text-black transition-all duration-200 ease-in-out"
            >
                Tambah Genre
                <Icon icon="tabler:plus" width="24" height="24" />
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-lg bg-gray-800 border-none">
            <DialogHeader>
                <DialogTitle class="text-white">Tambah Genre</DialogTitle>
                <DialogDescription class="text-muted-foreground">
                    Tambah genre baru
                </DialogDescription>
            </DialogHeader>
            <form id="form" @submit.prevent="submit">
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
</template>
