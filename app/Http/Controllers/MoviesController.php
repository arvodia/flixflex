<?php

namespace App\Http\Controllers;

use App\Models\Favories;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function abort;
use function env;
use function response;

class MoviesController extends Controller {

    public function top_movies(Request $request): JsonResponse {
        $key = env('THEMOVIEDB_KEY');
        try {
            $json = file_get_contents("https://api.themoviedb.org/3/movie/top_rated?api_key=$key&language=fr-FR&page=1");
            $result = json_decode($json, true);
        } catch (Exception $exc) {
            $result = ['results' => []];
        }
        $data = [];
        foreach ($result['results'] ?? [] as $key => $values) {
            if ($key > 5) {
                break;
            }
            $data[$key] = [
                'id' => $values['id'],
                'title' => $values['title'],
                'overview' => $values['overview'],
                'poster_path' => $values['poster_path'],
            ];
        }
        $result['results'] = $data;
        return response()->json($result, 200);
    }

    public function movies(Request $request): JsonResponse {
        $key = env('THEMOVIEDB_KEY');
        $page = $request->page ?: 1;
        try {
            $json = file_get_contents("https://api.themoviedb.org/3/trending/movie/week?api_key=$key&page=$page&language=fr-FR");
            $result = json_decode($json, true);
        } catch (Exception $exc) {
            $result = ['results' => []];
        }
        $data = [];
        foreach ($result['results'] ?? [] as $key => $values) {
            if ($key > 9) {
                break;
            }
            $data[$key] = [
                'id' => $values['id'],
                'title' => $values['title'],
                'overview' => $values['overview'],
                'poster_path' => $values['poster_path'],
            ];
        }
        $result['results'] = $data;
        return response()->json($result, 200);
    }

    public function movie(string $id): JsonResponse {
        $key = env('THEMOVIEDB_KEY');
        $json = file_get_contents("https://api.themoviedb.org/3/movie/$id?api_key=$key&language=fr-FR");

        $result = json_decode($json, true);

        try {
            $json = file_get_contents("https://api.themoviedb.org/3/movie/$id/videos?api_key=$key");
            $data = json_decode($json, true);
        } catch (Exception $exc) {
            $data = ['results' => []];
        }

        $result['trailer'] = false;
        foreach ($data['results'] as $values) {
            if (($values['type'] ?? null) === 'Trailer' &&
                    ($values['site'] ?? null) === 'YouTube') {
                $result['trailer'] = $values['key'];
            }
        }

        $fav = Favories::where('tmdb_id', $id)->first();
        $result['is_favorie'] = $fav ? true : false;
        return response()->json($result, 200);
    }

    public function favories(Request $request): JsonResponse {
        if (!Auth::check()) {
            abort(403);
        }
        $user = Auth::user();

        $result = ['results' => []];

        $favories = Favories::all()->where('user_id', $user->id);

        foreach ($favories as $favorie) {
            $result['results'][] = [
                'id' => $favorie->tmdb_id,
                'title' => $favorie->title,
                'overview' => $favorie->body,
                'poster_path' => $favorie->img,
            ];
        }

        return response()->json($result, 200);
    }

    public function favorie_add(string $id): JsonResponse {
        if (!Auth::check()) {
            abort(403);
        }
        $user = Auth::user();

        $key = env('THEMOVIEDB_KEY');
        $json = file_get_contents("https://api.themoviedb.org/3/movie/$id?api_key=$key&language=fr-FR");

        $result = json_decode($json, true);

        $fav = Favories::where('tmdb_id', $id)
                ->where('type', 'movie')
                ->where('user_id', $user->id)
                ->first();
        if (!$fav) {
            Favories::create([
                'tmdb_id' => $id,
                'type' => 'movie',
                'title' => $result['title'],
                'img' => $result['poster_path'],
                'body' => $result['overview'],
                'user_id' => $user->id,
            ]);
        }
        return response()->json([], 200);
    }

    public function favorie_remove(string $id): JsonResponse {
        if (!Auth::check()) {
            abort(403);
        }
        $user = Auth::user();
        Favories::where('tmdb_id', $id)
                ->where('type', 'movie')
                ->where('user_id', $user->id)
                ->delete();
        return response()->json([], 200);
    }

}
