<script setup>
import InputError from "@/Components/InputError.vue";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";
import Spinner from "@/Components/Spinner.vue";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
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
    <GuestLayout>
        <Head title="Reset Password" />

        <Card class="black-card rounded-xl w-full max-w-md relative z-10">
            <CardHeader class="text-center mb-3">
                <CardTitle class="text-3xl font-bold text-white">
                    Reset Password
                </CardTitle>
                <CardDescription class="text-gray-400 mt-2">
                    Silahkan masukkan kata sandi baru
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-6" @submit.prevent="submit">
                    <div>
                        <Label
                            for="email"
                            class="block text-sm font-medium text-gray-300 mb-1"
                        >
                            Email
                        </Label>
                        <Input
                            type="email"
                            id="email"
                            placeholder="Email"
                            v-model="form.email"
                            required
                            autocomplete="off"
                            class="w-full h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent focus-visible:ring-2 outline-none transition text-white"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="relative">
                        <Label
                            for="password"
                            class="block text-sm font-medium text-gray-300 mb-1"
                        >
                            Password
                        </Label>
                        <div class="relative">
                            <Input
                                type="password"
                                id="password"
                                placeholder="••••••••"
                                v-model="form.password"
                                required
                                autocomplete="off"
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
                                autocomplete="off"
                                class="w-full h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent focus-visible:ring-2 outline-none transition text-white"
                            />
                            <span
                                @click="showPassword('password_confirmation')"
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
                                :message="form.errors.password_confirmation"
                            />
                        </div>
                    </div>

                    <Button
                        type="submit"
                        :disabled="form.processing"
                        :class="{ 'opacity-50': form.processing }"
                        class="w-full bg-accent hover:bg-accent/60 transition-all ease-in-out duration-300 text-slate-900 disabled:cursor-not-allowed"
                    >
                        <Spinner v-show="form.processing" />
                        Reset Password
                    </Button>
                </form>
            </CardContent>
        </Card>
    </GuestLayout>
</template>

<style scoped>
.black-card {
    background: rgba(10, 10, 10, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.8), 0 0 30px rgba(59, 130, 246, 0.1);
}
</style>
