import {ref} from 'vue';
import apiPublic from '../api/apiPublic.js';

export default function useMovies() {

    const movies = ref([]);

    const getMovies = async () => {
        let response = await apiPublic().get('/api/top_movies');
        movies.value = response.data;
    }

    return {
        movies,
        getMovies,
    }
}