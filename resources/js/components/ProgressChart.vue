<script setup lang="ts">
import { LineChart } from '@/components/ui/chart-line';
import { onMounted, ref, watch } from 'vue';

const updateChart = defineModel<boolean>();

const props = defineProps<{
    targetId: number;
}>();

const chartData = ref<
    {
        time: string;
        Progress: number;
        Target: number;
    }[]
>([]);
const loading = ref(true);
const chartKey = ref(0);
const targetType = ref();

const fetchChartData = async () => {
    loading.value = true;
    const response = await fetch(route('targets.progress.chart', props.targetId));
    const data = await response.json();
    targetType.value = data.target.type;

    chartData.value = data.progress.map((p: { x: string; y: number }) => ({
        time: new Date(p.x).toLocaleString('de-DE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        }), // Convert string date to timestamp
        Progress: Number(p.y),
        Target: data.target.value as number,
    }));

    chartKey.value++; // Force chart refresh
    loading.value = false;
};

onMounted(() => {
    fetchChartData();
});

watch(
    () => [props.targetId, updateChart],
    () => {
        fetchChartData();
        updateChart.value = false;
    },
    { immediate: true, deep: true },
);
</script>

<template>
    <div class="relative min-h-[300px]">
        <div v-if="loading" class="absolute inset-0 flex items-center justify-center">Loading chart...</div>
        <LineChart
            v-else-if="chartData"
            :key="chartKey"
            :data="chartData"
            index="time"
            :categories="['Progress', 'Target']"
            :colors="['hsl(var(--chart-1))', 'hsl(var(--chart-2))']"
            :style="{ width: '100%', height: '100%' }"
            :x-formatter="
                (tick, i) => {
                    if (typeof tick !== 'number') {
                        return '';
                    }

                    if (Math.floor(tick) !== tick) {
                        return '';
                    }

                    return chartData[tick]?.time;
                }
            "
            :y-formatter="(tick, i) => `${tick} ${targetType}`"
        />
    </div>
</template>
