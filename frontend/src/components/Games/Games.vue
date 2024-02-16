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
    <div class="container">
        <div class="form-container">
            <h1>Add Game</h1>
            <GameForm @collectionUpdated="fetchGameCollection" />
        </div>
        <div class="list-container">
            <h1>Game Collection List</h1>
            <div class="game-list">
                <ul>
                    <li v-for="(game, index) in gameCollection" :key="index">
                        {{ game.id }} - {{ game.title }} - {{ game.publisher }} - {{ game.rating }} - {{ game.players }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
.container {
  display: flex;
}

.form-container {
  flex: 4;
  margin-right: 20px; /* Adjust margin as needed */
}

.list-container {
  flex: 4;
}
</style>