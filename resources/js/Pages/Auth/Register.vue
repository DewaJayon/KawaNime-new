<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import InputError from "@/Components/InputError.vue";
import { Icon } from "@iconify/vue";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

const showPassword = () => {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
};
const showPasswordConfirmation = () => {
    var x = document.getElementById("password_confirmation");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
};

const loginWithGoogle = () => {
    window.location.href = route("auth.redirect");
};
</script>

<template>
    <Head title="Register" />
    <GuestLayout>
        <Card class="black-card rounded-xl w-full max-w-md relative z-10">
            <CardHeader class="text-center mb-3">
                <CardTitle class="text-3xl font-bold text-white"
                    >Register</CardTitle
                >
                <CardDescription class="text-gray-400 mt-2"
                    >Buat akun untuk melanjutkan</CardDescription
                >
            </CardHeader>
            <CardContent>
                <form class="space-y-6" @submit.prevent="submit">
                    <div>
                        <Label
                            for="name"
                            class="block text-sm font-medium text-gray-300 mb-1"
                            >Nama</Label
                        >
                        <Input
                            type="name"
                            id="name"
                            placeholder="Nama"
                            v-model="form.name"
                            required
                            class="w-full h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent focus-visible:ring-2 outline-none transition text-white"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <Label
                            for="email"
                            class="block text-sm font-medium text-gray-300 mb-1"
                            >Email</Label
                        >
                        <Input
                            type="email"
                            id="email"
                            placeholder="Email"
                            v-model="form.email"
                            required
                            class="w-full h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent focus-visible:ring-2 outline-none transition text-white"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="relative">
                        <Label
                            for="password"
                            class="block text-sm font-medium text-gray-300 mb-1"
                            >Password</Label
                        >
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
                                @click="showPassword"
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
                            >Ulangi Password</Label
                        >
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
                                @click="showPasswordConfirmation"
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
                        class="w-full text-black bg-accent hover:bg-accent/60 transition-all ease-in-out duration-300"
                    >
                        Register
                    </Button>
                </form>

                <div class="divider text-sm">Atau</div>

                <button
                    @click="loginWithGoogle"
                    type="button"
                    class="w-full flex items-center justify-center gap-3 bg-white text-gray-800 hover:bg-gray-100 py-3 px-4 rounded-lg font-medium transition shadow-sm mb-4"
                >
                    <Icon
                        icon="flat-color-icons:google"
                        width="23"
                        height="23"
                    />
                    Login dengan Google
                </button>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        Sudah punya akun?
                        <Link
                            :href="route('login')"
                            class="text-accent hover:text-accent/60 transition-all ease-in-out duration-300 font-medium"
                            >Login</Link
                        >
                    </p>
                </div>
            </CardContent>
        </Card>
    </GuestLayout>
</template>

<style scoped>
.divider {
    display: flex;
    align-items: center;
    margin: 20px 0;
    color: #6b7280; /* gray-500 */
}
.divider::before,
.divider::after {
    content: "";
    flex: 1;
    border-bottom: 1px solid #374151; /* gray-700 */
}
.divider::before {
    margin-right: 10px;
}
.divider::after {
    margin-left: 10px;
}

.black-card {
    background: rgba(10, 10, 10, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.8), 0 0 30px rgba(59, 130, 246, 0.1);
}
</style>
