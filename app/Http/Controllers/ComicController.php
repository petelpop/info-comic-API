<?php

namespace App\Http\Controllers;
use App\Http\Resources\ComicDetailResource;
use App\Models\Comic;
use Illuminate\Http\Request;
use App\Http\Resources\ComicResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'eps' => 'required',
        ]);

        $image = null;
        $validextension = ['jpg', 'jpeg', 'png'];
        $extension = $request->file->extension();

        if(!in_array($extension, $validextension)){
            return response()->json([
                "Supported file : .jpg .jpeg .png"
            ]);
        }

        if ($request -> file) {
            $fileName = $this->generateRandomString();
            $image = $fileName. '.' .$extension;
            Storage::putFileAs('image', $request->file, $image);

        }

        $request['image'] = $image;
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

        $image = null;
        if($request -> file) {
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
        }

        $request['image'] = $image;
    }

    public function delete($id){
        $comic = Comic::findOrFail($id);
        $comic -> delete();

        return response()->json([
            'message' => 'Info Komik Berhasil Dihapus!'
        ]);

    }

    function generateRandomString($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
