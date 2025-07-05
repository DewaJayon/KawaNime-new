<script setup>
import InputError from "@/Components/InputError.vue";
import Spinner from "@/Components/Spinner.vue";
import { Button } from "@/Components/ui/button";
import { Progress } from "@/Components/ui/progress";
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
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from "@/Components/ui/number-field";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Icon } from "@iconify/vue";
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { useDropZone } from "@vueuse/core";
import { toast } from "vue-sonner";

const props = defineProps({
    anime: {
        type: Object,
        required: true,
    },
});

const isDialogOpen = ref(false);
const videoFile = ref(null);
const videoPreview = ref(null);
const fileInput = ref(null);

const form = useForm({
    title: "",
    episode_number: 1,
    duration: 1,
    video_url: null,
});

const dropZoneRef = ref(null);
useDropZone(dropZoneRef, {
    onDrop(files) {
        if (files.length > 0) handleVideoSelect(files[0]);
    },
});

const handleVideoSelect = (file) => {
    if (!file.type.startsWith("video/")) {
        toast.error("Hanya file video yang diperbolehkan!");
        return;
    }
    videoFile.value = file;
    videoPreview.value = URL.createObjectURL(file);
    form.video_url = file;
};

const openFilePicker = () => {
    fileInput.value?.click();
};

const submit = () => {
    if (!form.video_url) {
        toast.error("Video wajib diunggah.");
        return;
    }

    form.post(route("dashboard.episode.store", props.anime.slug), {
        preserveScroll: true,
        showProgress: false,
        onSuccess: () => {
            toast.success("Episode berhasil ditambahkan!", {
                description: "Resolusi video akan diproses di latar belakang.",
            });
            form.reset();
            videoFile.value = null;
            videoPreview.value = null;
            isDialogOpen.value = false;
        },
        onError: () => {
            toast.error("Terjadi kesalahan saat menyimpan.");
        },
    });
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger>
            <Button
                variant="outline"
                class="bg-transparent text-white hover:text-black transition-all"
            >
                Tambah Episode
                <Icon icon="tabler:plus" class="ml-2" width="20" height="20" />
            </Button>
        </DialogTrigger>

        <DialogContent
            class="max-w-[1000px] w-full max-h-[90vh] overflow-y-auto bg-gray-900 border-none rounded-xl shadow-lg custom-scrollbar"
        >
            <DialogHeader>
                <DialogTitle class="text-white"
                    >Tambah Episode {{ props.anime.title }}</DialogTitle
                >
                <DialogDescription class="text-muted-foreground"
                    >Masukkan informasi episode dan unggah
                    video.</DialogDescription
                >
            </DialogHeader>

            <form
                id="form"
                @submit.prevent="submit"
                class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6"
            >
                <div class="flex flex-col gap-4">
                    <div>
                        <Label class="text-white">Judul</Label>
                        <Input
                            type="text"
                            id="title"
                            placeholder="Sesuaikan dengan judul anime, contoh: Demon Slayer Episode 1"
                            v-model="form.title"
                            required
                            autocomplete="off"
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                        />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div>
                        <NumberField
                            id="episode_number"
                            :default-value="1"
                            :min="0"
                        >
                            <Label for="episode_number" class="text-white"
                                >Nomor Episode</Label
                            >
                            <NumberFieldContent>
                                <NumberFieldDecrement
                                    :disabled="
                                        form.episode_number === 1 ||
                                        form.episode_number === 0
                                    "
                                    @click="form.episode_number--"
                                    class="disabled:opacity-50 text-white"
                                />
                                <NumberFieldInput
                                    v-model="form.episode_number"
                                    @change="
                                        form.episode_number =
                                            $event.target.value
                                    "
                                    class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                                />
                                <NumberFieldIncrement
                                    @click="form.episode_number++"
                                    class="text-white"
                                />
                            </NumberFieldContent>
                        </NumberField>
                        <InputError :message="form.errors.episode_number" />
                    </div>

                    <div>
                        <NumberField id="duration" :default-value="1" :min="0">
                            <Label for="duration" class="text-white"
                                >Durasi Episode (Menit)</Label
                            >
                            <NumberFieldContent>
                                <NumberFieldDecrement
                                    :disabled="
                                        form.duration === 1 ||
                                        form.duration === 0
                                    "
                                    @click="form.duration--"
                                    class="disabled:opacity-50 text-white"
                                />
                                <NumberFieldInput
                                    @change="
                                        form.duration = $event.target.value
                                    "
                                    v-model="form.duration"
                                    class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                                />
                                <NumberFieldIncrement
                                    @click="form.duration++"
                                    class="text-white"
                                />
                            </NumberFieldContent>
                        </NumberField>
                        <InputError :message="form.errors.duration" />
                    </div>
                </div>

                <div>
                    <Label class="text-white">Unggah Video</Label>
                    <div
                        ref="dropZoneRef"
                        @click="openFilePicker"
                        class="mt-2 border-2 border-dashed border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:bg-gray-800 transition"
                    >
                        <div v-if="videoPreview">
                            <video
                                :src="videoPreview"
                                controls
                                class="w-full max-h-64 mx-auto rounded-lg"
                            ></video>
                            <p class="text-white mt-2 text-sm">
                                {{ videoFile?.name }}
                            </p>
                        </div>
                        <div
                            v-else
                            class="flex flex-col items-center justify-center gap-2 text-white"
                        >
                            <Icon icon="tabler:upload" width="40" height="40" />
                            <p>Klik atau tarik video ke sini</p>
                        </div>
                    </div>
                    <InputError :message="form.errors.video_url" />
                    <input
                        ref="fileInput"
                        type="file"
                        accept="video/mp4"
                        class="hidden"
                        @change="(e) => handleVideoSelect(e.target.files[0])"
                    />
                </div>
            </form>

            <DialogFooter>
                <Progress
                    :model-value="form.progress?.percentage ?? 0"
                    v-show="form.processing"
                    class="bg-zinc-600 mt-3 [&>*]:bg-white"
                />
                <DialogClose>
                    <Button
                        :disabled="form.processing"
                        variant="ghost"
                        class="mr-2 text-white hover:text-black disabled:opacity-50 transition-all duration-200 ease-in-out"
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
