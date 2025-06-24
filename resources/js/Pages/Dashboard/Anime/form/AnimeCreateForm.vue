<script setup>
import DropzoneImage from "@/Components/DropzoneImage.vue";
import InputError from "@/Components/InputError.vue";
import Spinner from "@/Components/Spinner.vue";
import { Button } from "@/Components/ui/button";
import { AnimeTypes, animeStatus } from "../partials/anime-enum";
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
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Icon } from "@iconify/vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import MultiSelect from "@/Components/MultiSelect.vue";
import DatePicker from "@/Components/DatePicker.vue";
import { Textarea } from "@/Components/ui/textarea";
import { toast } from "vue-sonner";

const props = defineProps({
    genres: {
        type: Array,
        required: true,
    },
});

const genreOptions = props.genres;

const isDialogOpen = ref(false);

const form = useForm({
    title: "",
    description: "",
    thumbnail: null,
    type: "",
    status: "",
    studio: "",
    release_date: null,
    director: "",
    genre_ids: [],
});

const submit = () => {
    form.post(route("dashboard.anime.store"), {
        preserveScroll: true,
        showProgress: false,
        onSuccess: () => {
            form.reset();
            toast.success("Anime berhasil ditambahkan");
            isDialogOpen.value = false;
        },
        onFinish: () => {
            form.reset();
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
                Tambah anime
                <Icon icon="tabler:plus" width="20" height="20" />
            </Button>
        </DialogTrigger>
        <DialogContent
            class="max-w-[800px] w-full max-h-[90vh] overflow-y-auto bg-gray-800 border-none rounded-xl shadow-lg custom-scrollbar"
        >
            <DialogHeader>
                <DialogTitle class="text-white">Tambah Anime</DialogTitle>
                <DialogDescription class="text-muted-foreground">
                    Tambah anime baru
                </DialogDescription>
            </DialogHeader>
            <form
                id="form"
                @submit.prevent="submit"
                class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4"
            >
                <!-- Thumbnail -->
                <div class="md:col-span-2">
                    <Label for="thumbnail" class="text-white">Thumbnail</Label>
                    <DropzoneImage v-model="form.thumbnail" />
                    <InputError :message="form.errors.thumbnail" />
                </div>

                <!-- Title -->
                <div>
                    <Label for="title" class="text-white">Judul</Label>
                    <Input
                        type="text"
                        id="title"
                        placeholder="Judul Anime"
                        v-model="form.title"
                        required
                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                    />
                    <InputError :message="form.errors.title" />
                </div>

                <!-- Studio -->
                <div>
                    <Label for="studio" class="text-white">Studio</Label>
                    <Input
                        type="text"
                        id="studio"
                        placeholder="Studio"
                        v-model="form.studio"
                        required
                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                    />
                    <InputError :message="form.errors.studio" />
                </div>

                <!-- Release Date -->
                <div>
                    <Label for="release_date" class="text-white"
                        >Tanggal Rilis</Label
                    >
                    <DatePicker
                        v-model="form.release_date"
                        placeholder="Pilih tanggal rilis"
                        dropdownClass="bg-slate-800 text-white"
                    />
                    <InputError :message="form.errors.release_date" />
                </div>

                <!-- Type -->
                <div>
                    <Label for="type" class="text-white">Tipe</Label>
                    <Select v-model="form.type" id="type" class="text-white">
                        <SelectTrigger
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                        >
                            <SelectValue placeholder="Pilih Tipe" />
                        </SelectTrigger>
                        <SelectContent class="bg-slate-800 text-white">
                            <SelectGroup>
                                <SelectLabel>Tipe</SelectLabel>
                                <SelectItem
                                    v-for="(AnimeTypes, index) in AnimeTypes"
                                    :key="index"
                                    :value="AnimeTypes.value"
                                    class="cursor-pointer hover:text-black focus:text-black"
                                >
                                    {{ AnimeTypes.label }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.type" />
                </div>

                <!-- Status -->
                <div>
                    <Label for="status" class="text-white">Status</Label>
                    <Select
                        v-model="form.status"
                        id="status"
                        class="text-white"
                    >
                        <SelectTrigger
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                        >
                            <SelectValue placeholder="Pilih Tipe" />
                        </SelectTrigger>
                        <SelectContent class="bg-slate-800 text-white">
                            <SelectGroup>
                                <SelectLabel>Tipe</SelectLabel>
                                <SelectItem
                                    v-for="(animeStatus, index) in animeStatus"
                                    :key="index"
                                    :value="animeStatus.value"
                                    class="cursor-pointer hover:text-black focus:text-black"
                                >
                                    {{ animeStatus.label }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.status" />
                </div>

                <div
                    v-if="form.type === 'movie' || form.type === 'live_action'"
                >
                    <Label for="director" class="text-white">Director</Label>
                    <Input
                        type="text"
                        id="director"
                        placeholder="Director"
                        v-model="form.director"
                        required
                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                    />
                    <InputError :message="form.errors.director" />
                </div>

                <!-- Genre -->
                <div class="md:col-span-2">
                    <Label for="genre" class="text-white">Genre</Label>
                    <MultiSelect
                        v-model="form.genre_ids"
                        :options="genreOptions"
                        placeholder="Pilih genre"
                        dropdownClass="bg-slate-800 text-white custom-scrollbar"
                    />
                    <InputError :message="form.errors.genre_ids" />
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <Label for="description" class="text-white"
                        >Deskripsi</Label
                    >
                    <Textarea
                        id="description"
                        placeholder="Deskripsi untuk Anime"
                        v-model="form.description"
                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                    />
                    <InputError :message="form.errors.description" />
                </div>
            </form>

            <DialogFooter>
                <DialogClose>
                    <Button
                        variant="ghost"
                        class="mr-2 text-white hover:text-black transition-all duration-200 ease-in-out"
                        >Batal</Button
                    >
                </DialogClose>
                <Button
                    :disabled="form.processing"
                    class="disabled:opacity-50 bg-zinc-600"
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
