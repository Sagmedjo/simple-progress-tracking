<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    target?: {
        id: number;
        title: string;
        description: string;
        target_value: number;
        target_type: string;
    };
    targetTypes: Array<{ label: string; value: string }>;
}>();

const form = useForm({
    title: props.target?.title ?? '',
    description: props.target?.description ?? '',
    target_value: props.target?.target_value ?? '',
    target_type: props.target?.target_type ?? '',
});

const submit = () => {
    if (props.target) {
        form.patch(route('targets.update', props.target.id));
    } else {
        form.post(route('targets.store'));
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Übersicht',
        href: route('dashboard'),
    },
    {
        title: 'Ziele',
        href: route('targets.index'),
    },
    {
        title: props.target ? 'Ziel bearbeiten' : 'Ziel erstellen',
        href: props.target ? route('targets.edit', props.target.id) : route('targets.create'),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="target ? 'Ziel bearbeiten' : 'Ziel erstellen'" />

        <div class="mx-auto w-full max-w-2xl p-6 lg:p-8">
            <h1 class="mb-6 text-2xl font-semibold">{{ target ? 'Ziel bearbeiten' : 'Ziel erstellen' }}</h1>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-2">
                    <Label for="title">Titel</Label>
                    <Input id="title" v-model="form.title" :error="form.errors.title" />
                </div>

                <div class="space-y-2">
                    <Label for="description">Beschreibung</Label>
                    <Textarea id="description" v-model="form.description" :error="form.errors.description" />
                </div>

                <div class="space-y-2">
                    <Label for="target_type">Typ</Label>
                    <Select v-model="form.target_type">
                        <SelectTrigger>
                            <SelectValue placeholder="Typ auswählen" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="type in targetTypes" :key="type.value" :value="type.value">
                                {{ type.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="space-y-2">
                    <Label for="target_value">Zielwert</Label>
                    <Input id="target_value" type="number" step="0.01" v-model="form.target_value" :error="form.errors.target_value" />
                </div>

                <div class="flex justify-end gap-4">
                    <Link :href="route('targets.index')">
                        <Button type="button" variant="outline">Abbrechen</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing">Speichern</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
