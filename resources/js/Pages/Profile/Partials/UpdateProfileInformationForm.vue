<script setup>
import InputError from "@/Components/InputError.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import Spinner from "@/Components/Spinner.vue";
import { toast } from "vue-sonner";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route("profile.update"), {
        preserveScroll: true,
        showProgress: false,
        onSuccess: () => {
            toast.success("Profile berhasil diubah");
        },
    });
};
</script>

<template>
    <div
        class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between"
    >
        <div class="w-full lg:w-4/5">
            <div class="lg:mb-6">
                <h4 class="text-lg font-semibold text-white/90">
                    Informasi Pribadi
                </h4>
                <p class="text-muted-foreground text-sm">
                    Perbarui informasi profil akun dan alamat email Anda.
                </p>
            </div>

            <form id="form" @submit.prevent="submit" class="w-full">
                <div
                    class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32 w-full"
                >
                    <div class="w-full">
                        <Label for="name" class="text-white">Nama</Label>
                        <Input
                            type="text"
                            id="name"
                            placeholder="Nama"
                            v-model="form.name"
                            required
                            autocomplete="off"
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="w-full">
                        <Label for="email" class="text-white">Email</Label>
                        <Input
                            :disabled="user.provider === 'google'"
                            type="text"
                            id="email"
                            placeholder="Email"
                            v-model="form.email"
                            required
                            autocomplete="off"
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white disabled:bg-slate-600 disabled:cursor-not-allowed"
                        />
                        <InputError :message="form.errors.email" />
                    </div>
                </div>
            </form>
        </div>

        <div class="w-full lg:w-1/5 flex justify-end">
            <Button
                form="form"
                variant="outline"
                :disabled="form.processing"
                class="bg-transparent rounded-xl text-accent border-accent hover:bg-gray-600/50 transition-all duration-500 ease-in-out disabled:cursor-not-allowed"
            >
                <Icon
                    icon="proicons:pencil"
                    width="24"
                    height="24"
                    v-show="!form.processing"
                />
                <Spinner v-show="form.processing" />
                {{ form.processing ? "Loading..." : "Simpan" }}
            </Button>
        </div>
    </div>
</template>
