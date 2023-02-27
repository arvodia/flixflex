import {ref} from 'vue';
import apiPublic from '../api/apiPublic.js';
import apiPrivate from '../api/apiPrivate.js';

export default function useMovies() {

    const movies = ref([]);

    const getMovies = async (page, q) => {
        page = page ? page : 1;
        let response = await apiPublic().get('/api/search-tv?q=' + q + '&page=' + page);
        movies.value = response.data;
    }

    return {
        movies,
        getMovies,
    }
}