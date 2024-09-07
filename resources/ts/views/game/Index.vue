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
            <div class="space-y-4">
                <div class="flex items-end space-x-4">
                    <div class="flex-auto">
                        <Button @click="createRoom" severity="warn" rounded>创建房间</Button>
                    </div>
                    <div class="flex-none">
                        <div class="flex items-center space-x-2">
                            <div class="px-2 flex items-center space-x-2">
                                <ToggleSwitch inputId="toggleSelf" />
                                <label for="toggleSelf">只看我的房间</label>
                            </div>
                            <div class="h-5 w-px bg-gray-200"></div>
                            <div class="px-2 flex items-center space-x-2 cursor-pointer">
                                <label class="text-gray-900">刷新</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div v-for="room in computedRoomList" class="px-6 py-2 flex items-center space-x-4 bg-white rounded-2xl shadow">
                        <div class="flex-none">
                            <div class="w-18 text-lg"># {{ room.code }}</div>
                        </div>
                        <div class="flex-auto">
                            <div class="flex flex-wrap">
                                <div class="w-9 h-9 flex items-center justify-center border border-gray-900 bg-white rounded-full">豆</div>
                                <div class="w-9 h-9 flex items-center justify-center border border-gray-900 bg-white -ml-4 rounded-full">大</div>
                                <div class="w-9 h-9 flex items-center justify-center border border-gray-900 bg-white -ml-4 rounded-full">猪</div>
                            </div>
                        </div>
                        <div class="flex-none">
                            <div class="flex items-center space-x-6">
                                <div class="text-sm" :class="[room.status.textColor]">{{ room.status.text }}</div>
                                <Button @click="handleToRoom(room.code)" severity="warn" rounded size="small">进入</Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { showGames } from "@/api/game";
import { rooms, storeRooms } from "@/api/room";

const route = useRoute();
const router = useRouter();

const game = ref({});

const roomList = ref([]);

const page = ref(1);

const gameKey = computed(() => {
    return route.params.gameKey;
})

const computedRoomList = computed(() => {
  return roomList.value.map(item => {
    let status = {
      textColor: 'text-red-500',
      text: '游戏中'
    };
    if (item.status === 'WAITING') {
      status = {
        textColor: 'text-gray-500',
        text: `${item.player_count}/${item.max_player} 等待中`
      }
    }

    return {
      ...item,
      status
    }
  })
})

onMounted(async () => {
    await getGameDetail();
    await getRoomList();

    setupChannel();
})

const setupChannel = () => {
    window.Echo.channel(`games.${gameKey.value}`)
        .listen('RoomListUpdated', async (e) => {
            const room = e.room;
            const index = roomList.value.findIndex(item => item.id === room.id);
            if (index > -1) {
              roomList.value.splice(index, 1, room);
            } else {
              roomList.value.unshift(room);
            }
        });
}

const getGameDetail = async () => {
    game.value = await showGames(gameKey.value)
}

const getRoomList = async () => {
    const res = await rooms({
        game_key: gameKey.value,
        page: page.value
    })
    roomList.value = res.data;
}

const createRoom = async () => {
    const room = await storeRooms({
        game_key: gameKey.value
    });

    handleToRoom(room.code);
}

const handleToRoom = (code) => {
    router.push({
        name: 'room.detail',
        params: {
            gameKey: gameKey.value,
            roomCode: code
        }
    })
}
</script>
