<?php

namespace App\Http\Controllers;
use App\Http\Resources\ComicDetailResource;
use App\Models\Comic;
use Illuminate\Http\Request;
use App\Http\Resources\ComicResource;
class ComicController extends Controller
{
    public function index(){
        $comics = Comic::all();
        return ComicResource::collection($comics);        
    }

    public function show($id){
        $comic = Comic::findOrFail($id);
        return new ComicDetailResource($comic);
    }
}
