import {ref} from 'vue';
import apiPublic from '../api/apiPublic.js';
import apiPrivate from '../api/apiPrivate.js';

export default function useMovies() {

    const movies = ref([]);
     
    const getMovies = async () => {
        let response = await apiPrivate().get('/api/favories');
        movies.value = response.data.results;
    }

    return {
        movies,
        getMovies,
    }
}