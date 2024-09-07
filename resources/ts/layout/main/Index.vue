<template>
    <div class="flex flex-col">
        <RouterView v-slot="{ Component, route }">
            <Transition appear>
                <Component :is="Component" :key="route.fullPath"/>
            </Transition>
        </RouterView>
    </div>
</template>