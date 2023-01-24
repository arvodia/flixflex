<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function env;
use function response;

class MoviesController extends Controller {

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
        return response()->json($result, 200);
    }
}
