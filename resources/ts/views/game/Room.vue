<template>
	<div class="space-y-4">
		<div class="w-full max-w-5xl mx-auto px-4">
			<div class="w-full flex items-center py-4">
				<div class="flex-auto">
					<div class="flex items-center space-x-2">
						<span class="text-3xl">{{ game.icon }}</span>
						<span class="text-gray-900 text-2xl font-semibold">{{ game.name }}</span>
					</div>
				</div>
				<div class="flex-none">
					<Button severity="warn" text rounded>💡 查看规则</Button>
				</div>
			</div>
		</div>
		<div class="w-full max-w-5xl mx-auto px-4">
			<div class="space-y-8">
				<div class="flex justify-center">
					<div class="flex flex-wrap justify-center gap-x-8 gap-y-4">
						<div class="space-y-1.5" v-for="item in computedPlayerList">
							<div class="relative w-18 h-18 flex items-center justify-center border-2 border-gray-900 rounded-full shadow-lg">
								<span v-if="item.player">{{ item.player.user_id }}</span>
								<Button v-else @click="handleJoin(item.sort)" severity="secondary" size="small" raised rounded>{{ isWatch ? '加入' : '换位' }}</Button>
								<div v-if="item.player && item.player.user_id === room.owner_id" class="absolute -top-2 inset-x-0 flex justify-center">
									<div class="px-1.5 py-0.5 leading-none rounded-full bg-orange-400 text-white">房主</div>
								</div>
								<div class="absolute bottom-0 left-0">
									<div class="px-1.5 py-0.5 leading-none rounded-full bg-orange-400 text-white">{{ item.sort }}</div>
								</div>
								<div v-if="item.player && item.player.user_id === userInfo.id" class="absolute bottom-0 right-0">
									<div class="px-1.5 py-0.5 leading-none rounded-full bg-orange-400 text-white">我</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-if="isWatch" class="text-center">
					<div class="text-xl text-gray-900">观战中</div>
					<div class="text-gray-500">选择座位加入游戏</div>
				</div>
				<div v-else class="flex justify-center">
					<Button @click="handleLeave" severity="secondary" size="small" raised rounded>离开座位，观战</Button>
				</div>
				<div v-if="isOwner" class="space-y-8">
					<div class="flex flex-wrap justify-center gap-4">
						<Button @click="handleUpdateMaxPlayer(i)" v-for="i in playerRange" severity="secondary" size="small" raised rounded :disabled="i < lastSort || room.max_player === i">{{ i }}</Button>
					</div>
					<div class="flex justify-center">
						<Button severity="secondary" size="small" raised rounded :disabled="!canStart">开始游戏</Button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script lang="ts" setup>
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { updateRooms, showRooms, joinRooms, leaveRooms } from "@/api/room";
import { useUserStore } from "@/store";

const route = useRoute();
const router = useRouter();

const userStore = useUserStore();

const game = ref({});
const room = ref({});
const playerList = ref([]);

const roomCode = computed(() => {
	return route.params.roomCode;
})

const userInfo = computed(() => {
	return userStore.userInfo;
})

const isWatch = computed(() => {
	return playerList.value.findIndex(item => item.user_id === userInfo.value.id) === -1;
})

const isOwner = computed(() => {
	return room.value.owner_id === userInfo.value.id;
})

const canStart = computed(() => {
	return playerList.value.length === room.value.max_player;
})

const playerRange = computed(() => {
	let range = [];
	for (let i = game.value.min_player; i <= game.value.max_player; i++) {
		range.push(i);
	}
	return range;
})

const lastSort = computed(() => {
	const sortList = playerList.value.sort(function(a, b) {
		return b.sort - a.sort;
	});

	return sortList.length > 0 ? sortList[0].sort : 0;
})

const computedPlayerList = computed(() => {
	let list = [];
	for (let i = 1; i <= room.value.max_player; i++) {
		const player = playerList.value.find(item => item.sort === i);
		list.push({
			sort: i,
			player
		});
	}

	return list;
})

onMounted(async () => {
	await getRoomDetail();
})

const setupChannel = () => {
	window.Echo.channel(`rooms.${room.value.id}`)
			.listen('RoomUpdated', async (e) => {
				room.value = e.room;
			})
			.listen('RoomPlayerJoined', async (e) => {
				const player = e.player;
				room.value = e.room;
				const index = playerList.value.findIndex(item => item.id === player.id);
				if (index > -1) {
					playerList.value.splice(index, 1, player);
				} else {
					playerList.value.push(player);
				}
			})
			.listen('RoomPlayerLeaved', async (e) => {
				room.value = e.room;
				playerList.value = e.players;
			});
}

const getRoomDetail = async () => {
	const res = await showRooms(roomCode.value);
	game.value = res.game;
	playerList.value = res.players;
	room.value = res;
	setupChannel();
}

const handleUpdateMaxPlayer = async (i) => {
	const room = await updateRooms(roomCode.value, {
		max_player: i
	})
}

const handleJoin = async (sort) => {
	const player = await joinRooms(roomCode.value, {
		sort
	});
}
const handleLeave = async (sort) => {
	await leaveRooms(roomCode.value);
}
</script>