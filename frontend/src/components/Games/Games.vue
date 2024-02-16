<script setup lang="ts">
import { Ref, ref, onMounted } from "vue";
import GameForm from "./GameForm.vue";

interface Game {
    id: number,
    title: string,
    publisher: string,
    rating?: number,
    players?: number
}

const gameCollection: Ref<Array<Game>> = ref([]);

const fetchGameCollection = async () => {
    try {
        const response = await fetch('/api/list');
        if (!response.ok) {
            throw new Error(`Failed to fetch: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        gameCollection.value = data;
        console.log('Game collection updated:', data);
    } catch (error) {
        console.error('Error gathering game collection:', error.message);
    }
};

onMounted(fetchGameCollection);

</script>

<template>
    <br>
    <div class="container">
        <div class="form-container">
            <h1 class="text-center text-xl">Add Game</h1>
            <GameForm @collectionUpdated="fetchGameCollection" />
        </div>
        <div class="list-container">
            <h1 class="text-center text-xl">Game Collection List</h1>
            <br>
            <div class="game-list">
                <div class="container justify-center mx-auto flex flex-col">
                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="inline-block min-w-full align-middle dark:bg-gray-800">
                            <div class="p-4">
                                <label for="table-search" class="sr-only">Search</label>
                                <div class="relative mt-1">
                                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="table-search"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Search for items" />
                                </div>
                            </div>
                            <div class="overflow-hidden">
                                <table class="min-w-full table-fixed dark:divide-gray-700 divide-y divide-green-400 ">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                Game Title
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                Game Publisher
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                Rating
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                Players
                                            </th>
                                            <th class="px-6 py-2 text-xs text-gray-500">
                                                Edit
                                            </th>
                                            <th class="px-6 py-2 text-xs text-gray-500">
                                                Delete
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700"
                                            v-for="(game, index) in gameCollection" :key="index">
                                            <td class="p-4 w-4">
                                            <td class="px-6 py-4">
                                                <div class="text-sm">
                                                    {{ game.title }}
                                                </div>
                                            </td>
                                            </td>
                                            <td class="p-4 w-4">
                                            <td class="px-6 py-4">
                                                <div class="text-sm">
                                                    {{ game.publisher }}
                                                </div>
                                            </td>
                                            </td>
                                            <td class="p-4 w-4">
                                            <td class="px-6 py-4">
                                                <div class="text-sm">
                                                    {{ game.rating }}
                                                </div>
                                            </td>
                                            </td>
                                            <td class="p-4 w-4">
                                            <td class="px-6 py-4">
                                                <div class="text-sm">
                                                    {{ game.players }}
                                                </div>
                                            </td>
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="#">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 
                      2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="#">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 
                      4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.container {
    display: flex;
}

.form-container {
    flex: 1;
    margin-right: 20px;
    /* Adjust margin as needed */
}

.list-container {
    flex: 2;
}
</style>