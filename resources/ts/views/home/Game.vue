<template>
    <div class="space-y-4">
        <div class="w-full max-w-6xl mx-auto px-4">
            <div class="w-full flex items-center py-4">
                <div class="flex-auto">
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl">🎮</span>
                        <span class="text-gray-900 text-2xl font-semibold">{{ game.name }}</span>
                    </div>
                </div>
                <div class="flex-none">
                    <Button rounded>登录</Button>
                </div>
            </div>
        </div>
		<div class="w-full max-w-6xl mx-auto px-4">
            
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { showGames } from "@/api/game";

const route = useRoute();
const router = useRouter();

const game = ref();

const gameKey = computed(() => {
    return route.params.key;
})

onMounted(async () => {
    await getGameDetail();
})

const getGameDetail = async () => {
    game.value = await showGames(gameKey.value)
}
</script>