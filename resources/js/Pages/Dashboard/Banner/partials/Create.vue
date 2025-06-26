<script setup>
import DropzoneImage from "@/Components/DropzoneImage.vue";
import InputError from "@/Components/InputError.vue";
import Spinner from "@/Components/Spinner.vue";
import { Button } from "@/Components/ui/button";
import { Switch } from "@/Components/ui/switch";
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
import { Textarea } from "@/Components/ui/textarea";
import { Icon } from "@iconify/vue";
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import axios from "axios";
import CustomCombobox from "@/Components/CustomCombobox.vue";
import { route } from "ziggy-js";

const isDialogOpen = ref(false);

const form = useForm({
    anime_id: "",
    headline: "",
    subheadline: "",
    image: "",
    is_active: true,
});

const submit = () => {
    alert(form.anime_id);
};

const animeOptions = ref([]);
const selectedAnimeId = ref("");

watch(selectedAnimeId, (val) => {
    form.anime_id = val;
});

const searchTerm = ref("");

watch(searchTerm, async (term) => {
    if (term.length < 2) return;

    try {
        const res = await axios.get(route("dashboard.banner.search.anime"), {
            params: { q: term },
        });

        animeOptions.value = res.data.map((item) => {
            return {
                id: item.id,
                title: item.title,
            };
        });
    } catch (e) {
        console.error(e);
    }
});
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
        <DialogContent
            class="max-w-[800px] w-full max-h-[90vh] overflow-y-auto bg-gray-800 border-none rounded-xl shadow-lg custom-scrollbar"
        >
            <DialogHeader>
                <DialogTitle class="text-white">Tambah Banner</DialogTitle>
                <DialogDescription class="text-white">
                    Tambah banner baru
                </DialogDescription>
            </DialogHeader>
            <form
                id="form"
                @submit.prevent="submit"
                class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4"
            >
                <div class="md:col-span-2">
                    <Label for="image" class="text-white">Banner (16:9)</Label>
                    <DropzoneImage v-model="form.image" />
                    <InputError :message="form.errors.image" />
                </div>

                <div>
                    <Label for="headline" class="text-white">Judul</Label>
                    <Input
                        type="text"
                        id="headline"
                        placeholder="Judul Banner"
                        v-model="form.headline"
                        required
                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                    />
                    <InputError :message="form.errors.headline" />
                </div>

                <div class="flex items-center py-8">
                    <Label for="headline" class="text-white pr-4">Aktif</Label>
                    <Switch
                        class="data-[state=checked]:bg-white data-[state=unchecked]:bg-slate-700 [&>*]:data-[state=checked]:bg-slate-700"
                        v-model="form.is_active"
                    />
                    <InputError :message="form.errors.is_active" />
                </div>

                <div class="md:col-span-2">
                    <Label for="anime_id" class="text-white pr-4">Anime</Label>
                    <CustomCombobox
                        v-model="selectedAnimeId"
                        :items="animeOptions"
                        id="anime_id"
                        valueKey="id"
                        labelKey="title"
                        placeholder="Pilih Anime"
                        v-model:searchTerm="searchTerm"
                    />
                </div>

                <div class="md:col-span-2">
                    <Label for="subheadline" class="text-white"
                        >Sub Judul</Label
                    >
                    <Textarea
                        id="subheadline"
                        placeholder="Sub Judul Banner"
                        v-model="form.subheadline"
                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                    />
                    <InputError :message="form.errors.subheadline" />
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
