<?php

namespace App\Http\Controllers;
use App\Http\Resources\ComicDetailResource;
use App\Models\Comic;
use Illuminate\Http\Request;
use App\Http\Resources\ComicResource;
use Illuminate\Support\Facades\Auth;

class ComicController extends Controller
{
    public function index(){
        $comics = Comic::all();
        return ComicDetailResource::collection($comics->loadMissing('writer:id,username', 'comments:id,comic_id,user_id,comment_content'));     
    }

    public function show($id){
        $comic = Comic::with('writer:id,username', 'comments:id,comic_id,user_id,comment_content')->findOrFail($id);
        return new ComicDetailResource($comic);
    }

    public function store(Request $request){
        $request -> validate([
            'title' => 'required|max:255',
            'prolog' => 'required',
            'eps' => 'required'
        ]);

        $request['author'] = Auth::user()->id;

        $comic = Comic::create($request->all());
        return new ComicDetailResource($comic->loadMissing('writer:id,username'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|max:255',
            'prolog' => 'required',
            'eps' => 'required'
        ]);

        return response()->json('Info Komik Berhasil Diubah!');
    }

    public function delete($id){
        $comic = Comic::findOrFail($id);
        $comic -> delete();

        return response()->json([
            'message' => 'Info Komik Berhasil Dihapus!'
        ]);
    }

}
