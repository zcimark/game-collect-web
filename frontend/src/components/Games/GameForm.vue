<script setup lang="ts">
import GameFormInput from '@/components/Games/GameFormInput.vue';
import { Game, fetchGameCollection } from '@/components/Games/Games.vue';
// import type { fetchGameCollection } from '@/components/Games/Games.vue';
import { ref, defineEmits } from 'vue';
import { useToast } from 'vue-toastification';
import 'vue-toastification/dist/index.css'

const game = ref<Game>({
        title: '',
        publisher: '',
        rating: 0,
        players: 0,
});

const toast = useToast();
const updateCollectionEmit = defineEmits(['collectionUpdated']);

const validateFields = () => {
    if (!game.value.title) {
        toast.error('You must enter a title for your game.');
        return;
    }

    if (!game.value.publisher) {
        toast.error('You must enter a publisher for your game.');
        return;
    }

}

const onSubmitForm = async () => {
    try {
        validateFields();
        let formData = new FormData();
        //append form inputs to the form data object and submit to symfony API 
        Object.keys(game.value).forEach(key => formData.append(key, game.value[key]));
        
        const response = await fetch('/api/create-game', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        });

        const data = await response.json();
        console.log(data);

        if (data.errors) {
            console.error('Form submission errors:', data.errors);
            //TODO
            // Handle form validation errors more efficiently
        } else {
            updateCollectionEmit('collectionUpdated');
            toast.success('Your "Game" ' + game.value.title + ' has been successfully added to the collection!');
        }
    } catch (error) {
        toast.error('Error submitting form:', error);
    }
};

</script>

<template>
    <form @submit.prevent="onSubmitForm">
        <GameFormInput>

            <template #heading>Title</template>
            <div class="game-input">
                <input class="game-input-box" v-model="game.title" placeholder="Enter Game Title" required />
            </div>
        </GameFormInput>

        <GameFormInput>
            <template #heading>Publisher for the game {{ game.title }}</template>
            <input class="game-input-box" v-model="game.publisher" placeholder="Enter Game Publisher"  />

            <br />
        </GameFormInput>

        <GameFormInput>

            <template #heading>Rating</template>

            <div>
                <input type="radio" id="rating1" v-model="game.rating" value="1" />
                <label for="rating1"> 1 </label>
                <input type="radio" id="rating2" v-model="game.rating" value="2" />
                <label for="rating1"> 2 </label>
                <input type="radio" id="rating3" v-model="game.rating" value="3" />
                <label for="rating1"> 3 </label>
                <input type="radio" id="rating4" v-model="game.rating" value="4" />
                <label for="rating1"> 4 </label>
                <input type="radio" id="rating5" v-model="game.rating" value="5" />
                <label for="rating1"> 5 </label>
            </div>
            <br />
        </GameFormInput>

        <GameFormInput>

            <template #heading>How many players can the game support?</template>
            <div>
                <input type="number" id="playerCount" v-model="game.players" />
                <label for="players">Players</label>
            </div>

            <br />
        </GameFormInput>

        <GameFormInput>
            <div class="form-group">
                <button class="submit-button" type="submit">Continue</button>
            </div>

        </GameFormInput>
    </form>
</template>

<style scoped>
:deep() {
    --vs-controls-color: #664cc3;
    --vs-border-color: #664cc3;

    --vs-dropdown-bg: #282c34;
    --vs-dropdown-color: #cc99cd;
    --vs-dropdown-option-color: #cc99cd;

    --vs-selected-bg: #664cc3;
    --vs-selected-color: #eeeeee;

    --vs-search-input-color: #eeeeee;

    --vs-dropdown-option--active-bg: #664cc3;
    --vs-dropdown-option--active-color: #eeeeee;
}

.game-input-box {
    padding: 8px;
    border-color: #664cc3;
    border-width: 1.5px;
    border-radius: 4px;
    font-size: 14px;
    background: #282c34;
    width: 100%;
    color: white !important;
}

.game-input {
    padding: 8px;
    border-radius: 4px;
    font-size: 14px;
    background: #282c34;
    width: 100%;
    color: white;
}

.submit-button {
    background-color: #4c96af;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.submit-button:hover {
    background-color: #664cc3;
}
</style>