<script setup>
import DropzoneImage from "@/Components/DropzoneImage.vue";
import InputError from "@/Components/InputError.vue";
import Spinner from "@/Components/Spinner.vue";
import { Button } from "@/Components/ui/button";
import { Switch } from "@/Components/ui/switch";
import { Check, ChevronsUpDown, Search } from "lucide-vue-next";
import { cn } from "@/lib/utils";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
import { Icon } from "@iconify/vue";
import { useForm } from "@inertiajs/vue3";
import { onBeforeMount, onUnmounted, ref, watch } from "vue";
import axios from "axios";
import { route } from "ziggy-js";
import { debounce } from "lodash";

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
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxList,
    ComboboxTrigger,
} from "@/Components/ui/combobox";

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

const selectedAnimeId = ref("");

watch(selectedAnimeId, (val) => {
    form.anime_id = val.id;
});

const animes = ref([]);
const searchInput = ref("");

const isLoading = ref(false);

watch(
    searchInput,
    debounce(async (val) => {
        isLoading.value = true;

        try {
            const res = await axios.get(
                route("dashboard.banner.search.anime", { q: val })
            );

            animes.value = res.data.map((anime) => {
                return {
                    value: anime.id,
                    label: anime.title,
                };
            });
        } catch (error) {
            console.error(error);
            isLoading.value = false;
        } finally {
            isLoading.value = false;
        }
    }, 500)
);

const stopPropagation = (event) => {
    event.stopImmediatePropagation();
};

onBeforeMount(() => {
    document.addEventListener("focusin", stopPropagation);
});

onUnmounted(() => {
    document.removeEventListener("focusin", stopPropagation);
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
                    <Combobox v-model="selectedAnimeId" by="label">
                        <ComboboxAnchor as-child>
                            <ComboboxTrigger as-child>
                                <Button
                                    variant="outline"
                                    class="justify-between w-full bg-transparent hover:bg-transparent border-slate-700 h-11 py-3 text-white"
                                >
                                    {{
                                        selectedAnimeId?.label ?? "Pilih Anime"
                                    }}

                                    <ChevronsUpDown
                                        class="ml-2 h-4 w-4 shrink-0 opacity-50"
                                    />
                                </Button>
                            </ComboboxTrigger>
                        </ComboboxAnchor>

                        <ComboboxList class="bg-slate-800">
                            <div class="relative w-full max-w-sm items-center">
                                <ComboboxInput
                                    v-model="searchInput"
                                    class="pl-9 focus-visible:ring-0 border-0 border-b rounded-none h-10 bg-transparent focus-visible:ring-offset-0 focus-visible:ring-transparent text-white"
                                    placeholder="Pilih Anime..."
                                />
                                <span
                                    class="absolute start-0 inset-y-0 flex items-center justify-center px-3"
                                >
                                    <Search class="size-4 text-white" />
                                </span>
                            </div>

                            <ComboboxEmpty
                                class="flex items-center justify-center text-white"
                            >
                                <Spinner v-if="isLoading" />
                                <template v-else>Tidak ada data</template>
                            </ComboboxEmpty>

                            <ComboboxGroup v-if="animes.length > 0">
                                <ComboboxItem
                                    v-for="anime in animes"
                                    :key="anime.value"
                                    :value="anime"
                                    class="relative px-4 py-2 cursor-pointer select-none hover:bg-slate-700 text-white hover:text-black data-[highlighted]:text-black"
                                >
                                    {{ anime.label }}

                                    <ComboboxItemIndicator>
                                        <Check :class="cn('ml-auto h-4 w-4')" />
                                    </ComboboxItemIndicator>
                                </ComboboxItem>
                            </ComboboxGroup>
                        </ComboboxList>
                    </Combobox>
                    <InputError :message="form.errors.anime_id" />
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
