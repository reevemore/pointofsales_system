<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />
    
    <div class="relative min-h-screen bg-slate-950 font-sans text-slate-300 selection:bg-indigo-500 selection:text-white overflow-hidden flex flex-col">
        
        <div class="absolute inset-0 z-0 flex items-center justify-center pointer-events-none">
            <div class="absolute top-[-15%] left-[-10%] h-[500px] w-[500px] rounded-full bg-indigo-600/20 blur-[120px]"></div>
            <div class="absolute bottom-[-15%] right-[-10%] h-[500px] w-[500px] rounded-full bg-cyan-600/20 blur-[120px]"></div>
        </div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <header class="w-full p-6 lg:p-8 flex justify-end items-center max-w-7xl mx-auto">
                <nav v-if="canLogin" class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-lg px-4 py-2 text-sm font-semibold text-white bg-slate-800/50 ring-1 ring-slate-700 hover:bg-slate-700 transition-all backdrop-blur-md"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/50 transition-all"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-lg bg-indigo-500 px-5 py-2.5 text-sm font-semibold text-white shadow-[0_0_15px_rgba(99,102,241,0.4)] hover:bg-indigo-400 transition-all hover:-translate-y-0.5"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="flex-1 flex flex-col items-center justify-center px-6 text-center lg:px-8">
                
                <div class="mb-8 inline-flex items-center gap-2 rounded-full border border-slate-700/60 bg-slate-800/40 px-5 py-2 text-sm font-medium text-slate-300 shadow-sm backdrop-blur-md transition-transform hover:scale-105 hover:border-indigo-500/50">
                    <span class="flex h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    <span>Crafted perfectly by</span>
                    <span class="font-bold text-white tracking-wide">jhonkontolotoz</span>
                </div>

                <h1 class="mx-auto max-w-4xl text-5xl font-extrabold tracking-tight text-white sm:text-7xl">
                    Welcome to the <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-cyan-400 to-teal-400">
                        Next Generation
                    </span>
                </h1>

                <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-400 sm:text-xl">
                    A beautifully designed, lightning-fast starting point for your web application. Powered by Vue, Tailwind CSS, and Laravel.
                </p>

                <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:justify-center">
                    <a
                        href="https://laravel.com/docs"
                        class="rounded-xl bg-white px-8 py-3.5 text-sm font-bold text-slate-900 shadow-lg shadow-white/10 hover:bg-slate-200 hover:shadow-white/20 transition-all hover:-translate-y-1"
                    >
                        Read Documentation
                    </a>
                    <a
                        href="https://laracasts.com"
                        class="rounded-xl border border-slate-700 bg-slate-900/50 px-8 py-3.5 text-sm font-bold text-white shadow-sm backdrop-blur-md hover:bg-slate-800 hover:border-slate-600 transition-all hover:-translate-y-1"
                    >
                        Watch Laracasts
                    </a>
                </div>
            </main>

            <footer class="w-full py-8 text-center text-sm text-slate-500">
                <p>
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </p>
            </footer>
        </div>
    </div>
</template>