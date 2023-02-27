import {ref} from 'vue';
import apiPublic from '../api/apiPublic.js';
import apiPrivate from '../api/apiPrivate.js';

export default function useMovies() {

    const movie = ref([]);
    const movies = ref([]);

    const getMovie = async (id) => {
        let response = await apiPublic().get('/api/serie/' + id);
        movie.value = response.data;
    }

    const addFavorie = async (id) => {
        let response = await apiPrivate().get('/api/favorie_tv/' + id + '/add');
    }

    const removeFavorie = async (id) => {
        let response = await apiPrivate().get('/api/favorie_tv/' + id + '/remove');
    }

    const getMovies = async (page) => {
        page = page ? page : 1;
        let response = await apiPublic().get('/api/series?page=' + page);
        movies.value = response.data;
    }

    return {
        movies,
        getMovies,
        getMovie,
        movie,
        addFavorie,
        removeFavorie
    }
}