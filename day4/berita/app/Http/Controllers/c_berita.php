<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\berita;

class c_berita extends Controller
{
    //
    public function index(){
        $data=berita::all();
        return view("berita.index",compact("data"));
    }
    public function create(){
        return view("berita.create");
    }
    public function store(Request $request){
        $berita=berita::create([
            'judul'=>$request->judul,
            'isi'=>$request->isi,
            'author'=>session('name'),
        ]);
        return redirect("/berita");
    }
    public function delete($id){
        berita::find($id)->delete();
        return redirect("/berita");
    }
}
