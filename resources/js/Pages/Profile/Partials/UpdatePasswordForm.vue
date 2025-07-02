<script setup>
import { Button } from "@/Components/ui/button";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { toast } from "vue-sonner";
import { Icon } from "@iconify/vue";
import Spinner from "@/Components/Spinner.vue";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import InputError from "@/Components/InputError.vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        showProgress: false,
        onSuccess: () => {
            form.reset();
            toast.success("Password berhasil diubah");
        },
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value.focus();
            }
        },
    });
};

const showPassword = (input) => {
    var x = document.getElementById(input);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
};
</script>

<template>
    <div
        class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between"
    >
        <div class="w-full lg:w-4/5">
            <div class="lg:mb-6">
                <h4 class="text-lg font-semibold text-white/90">
                    Update Password
                </h4>
                <p class="text-muted-foreground text-sm">
                    Pastikan akun Anda menggunakan kata sandi yang panjang dan
                    acak agar tetap aman.
                </p>
            </div>

            <form
                id="updatePassword"
                @submit.prevent="updatePassword"
                class="w-full"
            >
                <div
                    class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32 w-full"
                >
                    <div class="relative">
                        <Label
                            for="current_password"
                            class="block text-sm font-medium text-gray-300 mb-1"
                        >
                            Password Sekarang
                        </Label>
                        <div class="relative">
                            <Input
                                type="password"
                                id="current_password"
                                placeholder="••••••••"
                                v-model="form.current_password"
                                required
                                class="w-full h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent focus-visible:ring-2 outline-none transition text-white"
                            />
                            <span
                                @click="showPassword('current_password')"
                                class="absolute top-[50%] right-3 transform -translate-y-[50%] cursor-pointer text-gray-500 hover:text-accent transition-all ease-in-out duration-300"
                            >
                                <Icon
                                    icon="mdi-light:eye"
                                    width="30"
                                    height="30"
                                />
                            </span>

                            <InputError
                                class="mt-2"
                                :message="form.errors.current_password"
                            />
                        </div>
                    </div>

                    <div class="relative">
                        <Label
                            for="password"
                            class="block text-sm font-medium text-gray-300 mb-1"
                        >
                            Password Baru
                        </Label>
                        <div class="relative">
                            <Input
                                type="password"
                                id="password"
                                placeholder="••••••••"
                                v-model="form.password"
                                required
                                class="w-full h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent focus-visible:ring-2 outline-none transition text-white"
                            />
                            <span
                                @click="showPassword('password')"
                                class="absolute top-[50%] right-3 transform -translate-y-[50%] cursor-pointer text-gray-500 hover:text-accent transition-all ease-in-out duration-300"
                            >
                                <Icon
                                    icon="mdi-light:eye"
                                    width="30"
                                    height="30"
                                />
                            </span>

                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>
                    </div>

                    <div class="relative">
                        <Label
                            for="password_confirmation"
                            class="block text-sm font-medium text-gray-300 mb-1"
                        >
                            Konfirmasi Password
                        </Label>
                        <div class="relative">
                            <Input
                                type="password"
                                id="password_confirmation"
                                placeholder="••••••••"
                                v-model="form.password_confirmation"
                                required
                                class="w-full h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent focus-visible:ring-2 outline-none transition text-white"
                            />
                            <span
                                @click="showPassword('password_confirmation')"
                                class="absolute top-[50%] right-3 transform -translate-y-[50%] cursor-pointer hover:text-accent transition-all ease-in-out duration-300"
                            >
                                <Icon
                                    icon="mdi-light:eye"
                                    width="30"
                                    height="30"
                                />
                            </span>

                            <InputError
                                class="mt-2"
                                :message="form.errors.password_confirmation"
                            />
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <Button
            form="updatePassword"
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
</template>
