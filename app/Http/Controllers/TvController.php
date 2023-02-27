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

class TvController extends Controller {

    public function top_tv(Request $request): JsonResponse {
        $key = env('THEMOVIEDB_KEY');
        try {
            $json = file_get_contents("https://api.themoviedb.org/3/tv/top_rated?api_key=$key&language=fr-FR&page=1");
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
                'title' => $values['name'],
                'overview' => $values['overview'],
                'poster_path' => $values['poster_path'],
            ];
        }
        $result['results'] = $data;
        return response()->json($result, 200);
    }

    public function series(Request $request): JsonResponse {
        $key = env('THEMOVIEDB_KEY');
        $page = $request->page ?: 1;
        try {
            $json = file_get_contents("https://api.themoviedb.org/3/trending/tv/week?api_key=$key&page=$page&language=fr-FR");
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
                'title' => $values['name'],
                'overview' => $values['overview'],
                'poster_path' => $values['poster_path'],
            ];
        }
        $result['results'] = $data;
        return response()->json($result, 200);
    }

    public function serie(string $id): JsonResponse {
        $key = env('THEMOVIEDB_KEY');
        $json = file_get_contents("https://api.themoviedb.org/3/tv/$id?api_key=$key&language=fr-FR");

        $result = json_decode($json, true);

        try {
            $json = file_get_contents("https://api.themoviedb.org/3/tv/$id/videos?api_key=$key");
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

    public function favorie_add(string $id): JsonResponse {
        if (!Auth::check()) {
            abort(403);
        }
        $user = Auth::user();

        $key = env('THEMOVIEDB_KEY');
        $json = file_get_contents("https://api.themoviedb.org/3/tv/$id?api_key=$key&language=fr-FR");

        $result = json_decode($json, true);

        $fav = Favories::where('tmdb_id', $id)
                ->where('type', 'serie')
                ->where('user_id', $user->id)
                ->first();
        if (!$fav) {
            Favories::create([
                'tmdb_id' => $id,
                'type' => 'serie',
                'title' => $result['name'],
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
                ->where('type', 'serie')
                ->where('user_id', $user->id)
                ->delete();
        return response()->json([], 200);
    }

}
