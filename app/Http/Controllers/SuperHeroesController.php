<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SuperHeroesRequest;
use App\Models\SuperHeroes;
use App\Models\Images;
use App\Helpers\Image;
use Validator;

class SuperHeroesController extends Controller
{
    public function show(SuperHeroes $superheroes, Images $images)
    {
		$superheroes = $superheroes->select()->with('images')->paginate(5);
        return view('index', ['superheroes' => $superheroes]);
	}
	
	public function create()
	{
		return view('create');
	}

	public function details(SuperHeroes $superheroes, $id)
	{
		$hero = $superheroes::find($id);

		if($hero){
			return view('details', ['hero' => $hero]);
		}

		return redirect('/');
	}

    public function save(SuperHeroesRequest $request, SuperHeroes $superheroes)
    {
		if(!Image::validate($request->images)){
			return view('create', ['message' => 'Caminho da imagem inválido!']);
		}

		$imagesDirectoryPermanent = Image::saveFilePermanent($request->images, $request->nickname);

		$superheroes->nickname = $request->nickname;
		$superheroes->real_name = $request->real_name;
		$superheroes->origin_description = $request->origin_description;
		$superheroes->superpowers = $request->superpowers;
		$superheroes->catch_phrase = $request->catch_phrase;
		$superheroes->save();

		foreach($imagesDirectoryPermanent as $file){
			$images = new Images();
			$images->file = $file;
			$superheroes->images()->save($images);
		}

		return redirect('/');
	}

	public function update(SuperHeroesRequest $request, SuperHeroes $superheroes, Images $images)
	{
		$result = $superheroes::find($request->id);

		if(!$result){
			return redirect('/');
		}

		if(!Image::validate($request->images)){
			return view('edit', ['message' => 'Caminho da imagem inválido!']);
		}

		$deletedFiles = Image::checkDeletedFiles($request->images, $result->images);
		
		if(!empty($deletedFiles)){
			Image::removeAllFilesPermanent($deletedFiles['files'], $result->nickname, false);
			$result->images()->whereIn('id', $deletedFiles['ids'])->delete();
		}

		$newFiles = Image::checkNewFiles($result->images, $request->images);

		if(!empty($newFiles)){
			$imagesDirectoryPermanent = Image::saveFilePermanent($newFiles, $result->nickname, false);
			
			$result->real_name = $request->real_name;
			$result->origin_description = $request->origin_description;
			$result->superpowers = $request->superpowers;
			$result->catch_phrase = $request->catch_phrase;
			$result->save();
	
			foreach($imagesDirectoryPermanent as $file){
				$images = new Images();
				$images->file = $file;
				$result->images()->save($images);
			}
		}
		
		return redirect('/');
	}
	
	public function remove(SuperHeroes $superheroes, $id)
	{
		$result = $superheroes::find($id);
		
		if($result){
			Image::removeAllFilesPermanent($result->images, $result->nickname);
			$result->delete();
			$result->images()->delete();
		}

		return redirect('/');
	}

	public function edit(SuperHeroes $superheroes, $id)
	{
		$hero = $superheroes::find($id);

		if($hero){
			return view('edit', ['hero' => $hero]);
		}

		return redirect('/');
	}

    public function addImage(Request $request)
    {
    	$validator = \Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

	 	if ($validator->fails())
	  	{
	    	return response()->json([
   				'success' => false,
				'error' => 'Formato da imagem inválido ou imagem não selecionada!'
			]);
	  	}

	  	Image::saveFileTemporary(
	  		$request->file('image')->getPathName(), 
	  		$request->file('image')->getClientOriginalExtension()
	  	);
    	
   		return response()->json([
   			'success' => true,
   			'file' => '/images' . $request->file('image')->getPathName() . '.' . $request->file('image')->getClientOriginalExtension(),
		]);
	}
	
	public function removeImage(Request $request)
	{	
		if(!Image::validate($request->image)){
			return response()->json([
				'success' => false,
				'error' => 'Caminho da imagem inválido!'
			]);
		}

		if(!Image::removeFile($request->image)){
			return response()->json([
				'success' => false,
				'error' => 'Erro ao remover arquivo!'
			]);	
		}
		
		return response()->json(['success' => true]);
	}
}
