<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
</script>

<template>
    <Head title="Welcome" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-background p-4">
        <div class="mx-auto max-w-4xl space-y-8 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-foreground sm:text-5xl md:text-6xl">Simple Progress Tracking</h1>
            <p class="text-lg text-muted-foreground sm:text-xl">Die Plattform für alles, was du brauchst, um deine Produktivität zu steigern.</p>
            <div class="mt-8 flex flex-col justify-center gap-4 sm:flex-row">
                <Link :href="route('login')">
                    <Button variant="outline" size="lg"> Einloggen </Button>
                </Link>
                <Link :href="route('register')">
                    <Button variant="default" size="lg">
                        Registrieren
                        <ArrowRight class="ml-2 h-4 w-4" />
                    </Button>
                </Link>
            </div>
        </div>
    </div>
</template>
