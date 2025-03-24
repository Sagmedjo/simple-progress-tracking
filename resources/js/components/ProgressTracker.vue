<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    targetId: number;
}>();

const form = useForm({
    value: '',
    tracked_at: new Date().toISOString().slice(0, 10), // Format: YYYY-MM-DD
});

const submit = () => {
    form.post(route('targets.progress.store', props.targetId), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            $emit('progress-tracked');
        },
    });
};

const $emit = defineEmits<{
    'progress-tracked': [];
}>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Fortschritt eintragen</CardTitle>
        </CardHeader>
        <CardContent>
            <form @submit.prevent="submit" class="flex gap-4">
                <Input type="number" step="0.01" v-model="form.value" placeholder="Wert eingeben" :error="form.errors.value" />
                <Input type="date" v-model="form.tracked_at" :error="form.errors.tracked_at" />
                <Button type="submit" :disabled="form.processing">Eintragen</Button>
            </form>
        </CardContent>
    </Card>
</template>
