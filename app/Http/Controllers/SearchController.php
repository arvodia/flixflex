<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function env;
use function response;

class SearchController extends Controller {

    public function search_movies(Request $request): JsonResponse {
        $key = env('THEMOVIEDB_KEY');
        $page = $request->page ?: 1;
        $q = $request->q;
        try {
            $json = file_get_contents("https://api.themoviedb.org/3/search/movie?api_key=$key&query=$q&page=$page&language=fr-FR");
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

    public function search_tv(Request $request): JsonResponse {
        $key = env('THEMOVIEDB_KEY');
        $page = $request->page ?: 1;
        $q = $request->q;
        try {
            $json = file_get_contents("https://api.themoviedb.org/3/search/tv?api_key=$key&query=$q&page=$page&language=fr-FR");
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

}
