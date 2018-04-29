<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\Models\SuperHeroes;
use App\Models\Images;

class SuperHeroesTest extends TestCase
{
    private $superheroes;
    private $images;

    public function setUp()
	{
        parent::setUp();

        $this->superheroes = new SuperHeroes;
        $this->images = new Images();

		\DB::beginTransaction();
    }

    public function testShowSuperHeroes()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testPageCreateSuperHeroes()
    {
        $response = $this->get('create');
        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $this->superheroes->nickname = 'teste';
        $this->superheroes->real_name = 'teste';
        $this->superheroes->origin_description = 'teste';
        $this->superheroes->superpowers = 'teste';
        $this->superheroes->catch_phrase = 'teste';
        $this->superheroes->save();

        $response = $this->get("edit/{$this->superheroes->id}");
        $response->assertStatus(200);
    }

    public function testEditInvalid()
    {
        $response = $this->get('edit/1');
        $response->assertStatus(302);
    }

    public function testDetails()
    {
        $this->superheroes->nickname = 'teste';
        $this->superheroes->real_name = 'teste';
        $this->superheroes->origin_description = 'teste';
        $this->superheroes->superpowers = 'teste';
        $this->superheroes->catch_phrase = 'teste';
        $this->superheroes->save();

        $response = $this->get("details/{$this->superheroes->id}");
        $response->assertStatus(200);
    }

    public function testDetailsInvalid()
    {
        $response = $this->get('details/1');
        $response->assertStatus(302);
    }

    public function testRemove()
    {
        mkdir(base_path(). "/public/images/teste");
        file_put_contents("public/images/teste/teste-document.jpg", true);

        $this->superheroes->nickname = 'teste';
        $this->superheroes->real_name = 'teste';
        $this->superheroes->origin_description = 'teste';
        $this->superheroes->superpowers = 'teste';
        $this->superheroes->catch_phrase = 'teste';
        $this->superheroes->save();

        
        $this->images->file = '/images/teste/teste-document.jpg';
        $this->superheroes->images()->save($this->images);
        
        $response = $this->get("remove/{$this->superheroes->id}");
        $response->assertStatus(302);
    }

    public function testUpdateSuccess()
    {
        mkdir(base_path(). "/public/images/teste");
        file_put_contents("public/images/teste/teste-document.jpg", true);
        file_put_contents("public/images/tmp/document2.jpg", true);

        $this->superheroes->nickname = 'teste';
        $this->superheroes->real_name = 'teste';
        $this->superheroes->origin_description = 'teste';
        $this->superheroes->superpowers = 'teste';
        $this->superheroes->catch_phrase = 'teste';
        $this->superheroes->save();
        
        $this->images->file = '/images/teste/teste-document.jpg';
        $this->superheroes->images()->save($this->images);

        $response = $this->json('POST', '/update', array(
            'id' => $this->superheroes->id,
            'real_name' => 'teste',
            'origin_description' => 'teste',
            'superpowers' => 'teste',
            'catch_phrase' => 'teste',
            'images' => ['/images/tmp/document2.jpg']
        ));

        unlink(base_path()."/public/images/teste/teste-document2.jpg");
        rmdir(base_path()."/public/images/teste");

        $response->assertStatus(302);
    }

    public function testUpdateFailInvalidDatas()
    {
        $this->superheroes->nickname = 'teste';
        $this->superheroes->real_name = 'teste';
        $this->superheroes->origin_description = 'teste';
        $this->superheroes->superpowers = 'teste';
        $this->superheroes->catch_phrase = 'teste';
        $this->superheroes->save();
        
        $this->images->file = '/images/teste/teste-document.jpg';
        $this->superheroes->images()->save($this->images);

        $response = $this->json('POST', '/update', array(
            'id' => $this->superheroes->id,
            'real_name' => '',
            'origin_description' => '',
            'superpowers' => '',
            'catch_phrase' => '',
            'images' => ['']
        ));

        $response->assertStatus(422);
    }
    
    public function testSaveSuccess()
    {
        file_put_contents("public/images/tmp/document.jpg", true);

        $response = $this->json('POST', '/save', array(
			'nickname' => 'teste',
            'real_name' => 'teste',
            'origin_description' => 'teste',
            'superpowers' => 'teste',
            'catch_phrase' => 'teste',
            'images' => ['/images/tmp/document.jpg']
        ));

        unlink(base_path()."/public/images/teste/teste-document.jpg");
        rmdir(base_path()."/public/images/teste");
        
        $response->assertRedirect('/');
    }

    public function testSaveFailRepeated()
    {
        $this->superheroes::create([
            'nickname' => 'teste',
            'real_name' => 'teste',
            'origin_description' => 'teste',
            'superpowers' => 'teste',
            'catch_phrase' => 'teste',
            'images' => ['/images/tmp/document.jpg']
        ]);

        $response = $this->json('POST', '/save', array(
			'nickname' => 'teste',
            'real_name' => 'teste',
            'origin_description' => 'teste',
            'superpowers' => 'teste',
            'catch_phrase' => 'teste',
            'images' => ['/images/tmp/document.jpg']
        ));

        $response->assertStatus(422);
    }

    public function testSaveFailInvalidDatas()
    {
        $response = $this->json('POST', '/save', array(
			'nickname' => '',
            'real_name' => '',
            'origin_description' => '',
            'superpowers' => '',
            'catch_phrase' => '',
            'images' => ['']
        ));
        
        $response->assertStatus(422);
    }

    public function testAddImageSuccess()
    {
        $response = $this->json('POST', '/add-image', [
            'image' => UploadedFile::fake()->create('document.jpg', 300)
        ])->decodeResponseJson();

        $this->assertEquals(true, $response['success']);
    }

    public function testAddImageFail()
    {
        $response = $this->json('POST', '/add-image', [
            'image' => UploadedFile::fake()->create('document.pdf', 300)
        ])->decodeResponseJson();

        $this->assertEquals(false, $response['success']);
    }

    public function testRemoveImageSuccess()
    {
        file_put_contents("public/images/tmp/document.jpg", true);

        $response = $this->json('POST', '/remove-image', [
            'image' => '/images/tmp/document.jpg',
            'nickname' => null
        ])->decodeResponseJson();

        $this->assertEquals(true, $response['success']);
    }

    public function testRemoveImageFailFileInvalid()
    {
        $response = $this->json('POST', '/remove-image', [
            'image' => '/images/tmp/document.pdf',
            'nickname' => null
        ])->decodeResponseJson();

        $this->assertEquals(false, $response['success']);
    }

    public function testRemoveImageFailFileNotExist()
    {
        $response = $this->json('POST', '/remove-image', [
            'image' => '/images/tmp/document.jpg',
            'nickname' => null
        ])->decodeResponseJson();

        $this->assertEquals(false, $response['success']);
    }

    public function tearDown()
    {
		\DB::rollback();
	}
}
