<template>
    <div class="space-y-4">
        <div class="w-full max-w-6xl mx-auto px-4">
            <div class="w-full flex items-center py-4">
                <div class="flex-auto">
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl">🎮</span>
                        <span class="text-gray-900 text-2xl font-semibold">Pigame</span>
                    </div>
                </div>
                <div class="flex-none">
                    <Button @click="loginVisible = true" rounded>登录</Button>
                </div>
            </div>
        </div>
		<div class="w-full max-w-6xl mx-auto px-4">
            <div class="space-y-2" v-for="category in categoryList">
                <div class="text-lg font-semibold">{{ category.name }}</div>
                <div class="flex items-center space-x-2">
                    <div v-for="game in category.games" @click="handleDetail(game.key)" class="flex items-center space-x-2 px-6 py-2 bg-orange-300 rounded-xl shadow-md cursor-pointer">
                        <div class="text-4xl py-3">{{ game.icon }}</div>
                        <div class="space-y-1">
                            <div class="text-lg font-semibold">{{ game.name }}</div>
                            <div class="text-gray-700 text-sm">2-10+人</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:visible="loginVisible" modal header="用户登录" :style="{ width: '25rem' }">
            <div class="space-y-4">
                <div class="flex flex-col gap-2">
                    <label for="name">用户名</label>
                    <InputText v-model="loginForm.name" id="name" class="flex-auto" autocomplete="off" placeholder="输入用户名" />
                </div>
                <div class="flex flex-col gap-2">
                    <label for="password">密码</label>
                    <InputText v-model="loginForm.password" id="password" class="flex-auto" autocomplete="off" placeholder="输入密码" />
                </div>
                <div class="flex justify-end gap-2">
                    <Button @click="loginVisible = false" severity="secondary" rounded>取消</Button>
                    <Button @click="handleLogin" rounded>登录</Button>
                </div>
            </div>
        </Dialog>
    </div>
</template>

<script lang="ts" setup>
import { useRouter } from "vue-router";
import { gameCategories } from "@/api/game";
import { onMounted, ref } from "vue";
import {useUserStore} from "@/store";

const router = useRouter();

const userStore = useUserStore();

const categoryList = ref([]);

const loginVisible = ref(false);

const loginForm = ref({
	name: '',
	password: ''
})

onMounted(async () => {
    await getCategoryList();
});

const getCategoryList = async () => {
    categoryList.value = await gameCategories();
};

const handleDetail = (gameKey) => {
    router.push({
        name: 'game.detail',
        params: {
            gameKey
        }
    })
}

const handleLogin = async () => {
	await userStore.login(loginForm.value);

	loginVisible.value = false;
	// router.go(0);
}
</script>