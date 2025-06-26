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
import { router, useForm } from "@inertiajs/vue3";
import { onBeforeMount, onUnmounted, ref, watch } from "vue";
import axios from "axios";
import { route } from "ziggy-js";
import { toast } from "vue-sonner";

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

const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});

const isDialogOpen = ref(false);
const isLoading = ref(false);
const animes = ref(props.row.anime);
const searchInput = ref("");
const isSubmitting = ref(false);

const image = ref(props.row.image);

const form = useForm({
    anime_id: props.row.anime.id,
    headline: props.row.headline,
    subheadline: props.row.subheadline,
    image: image,
    is_active: props.row.is_active == 1 ? true : false,
});

watch(image, (newValue) => {
    form.reset("image");
    form.image = newValue;
});

const submit = () => {
    isSubmitting.value = true;

    if (!(form.image instanceof File)) {
        form.image = null;
    }

    router.post(
        route("banner.update", props.row.id),
        {
            _method: "put",
            ...form,
        },
        {
            preserveScroll: true,
            showProgress: false,
            onSuccess: () => {
                toast.success("Banner berhasil diubah");
                isDialogOpen.value = false;
                form.reset("image");
            },
            onError: (e) => {
                toast.error(e);
            },
            onFinish: () => {
                isSubmitting.value = false;
            },
        }
    );
};

const selectedAnimeId = ref({
    value: props.row.anime.id,
    label: props.row.anime.title,
});

watch(selectedAnimeId, (anime) => {
    form.anime_id = anime.value;
});

watch(searchInput, (value) => {
    isLoading.value = true;
    axios
        .get(route("dashboard.banner.search.anime", { q: value }))
        .then((res) => {
            animes.value = res.data.map((anime) => ({
                value: anime.id,
                label: anime.title,
            }));
        })
        .finally(() => {
            isLoading.value = false;
        });
});

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
        <DialogTrigger as-child>
            <Button
                variant="ghost"
                size="icon"
                class="hover:bg-transparent whitespace-nowrap"
            >
                <Icon
                    icon="material-symbols:edit-outline-rounded"
                    class="text-blue-500 text-lg"
                />
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
                    <DropzoneImage v-model="image" />
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
                        autocomplete="off"
                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                    />
                    <InputError :message="form.errors.headline" />
                </div>

                <div class="flex items-center py-8">
                    <Label for="headline" class="text-white pr-4">Aktif</Label>
                    <Switch
                        class="data-[state=checked]:bg-accent data-[state=unchecked]:bg-slate-700 [&>*]:data-[state=checked]:bg-slate-700"
                        v-model="form.is_active"
                    />
                    <InputError :message="form.errors.is_active" />
                </div>

                <div class="md:col-span-2 relative w-full">
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

                        <ComboboxList class="!w-auto bg-slate-800 text-white">
                            <div class="relative w-full items-center">
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
                    :disabled="isSubmitting"
                    class="disabled:opacity-50 bg-zinc-600"
                    type="submit"
                    form="form"
                >
                    <Spinner v-show="isSubmitting" />
                    {{ isSubmitting ? "Menyimpan..." : "Simpan" }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
