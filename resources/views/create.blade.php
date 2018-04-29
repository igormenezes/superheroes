<!DOCTYPE html>
<html>
<head>
@include('layout/head')
</head>
<body>
@include('layout/nav')
<div class="container">
<form id='formulario' action="/save" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="nickname">Nickname</label>
    <input type="text" class="form-control" name='nickname' value="{{ old('nickname') }}" placeholder="Nickname" required>
  </div>
  <div class="form-group">
    <label for="real_name">Real name</label>
    <input type="text" class="form-control" name='real_name' value="{{ old('real_name') }}" placeholder="Real name" required>
  </div>
  <div class="form-group">
    <label for="origin_description">Origin description</label>
    <textarea class="form-control" name='origin_description' placeholder="Origin description" rows="3" required>{{ old('origin_description') }}</textarea>
  </div>
  <div class="form-group">
    <label for="superpowers">Super powers</label>
    <textarea class="form-control" name='superpowers' placeholder="Super powers" rows="3" required>{{ old('superpowers') }}</textarea>
  </div>
  <div class="form-group">
    <label for="catch_phrase">Catch Phrase</label>
    <textarea class="form-control" name='catch_phrase' placeholder="Catch Phrase" rows="3" required>{{ old('catch_phrase') }}</textarea>
  </div>
  <div id='all-box-images'></div>
  <div class="form-group">
    <div class="input-group">
      <div class="custom-file">
        <input type="file" class="custom-file-input" id="superheroes-image">
        <label class="custom-file-label" for="superheroes-image">Choose file</label>
      </div>
      <div class="input-group-append">
        <button id="add-image" class="btn btn-outline-secondary" type="button">Add Image</button>
      </div>
    </div>
  </div>
  <div id='error'></div>
  @if (count($errors) > 0)
    <div class="alert alert-danger errors">
      @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
      @endforeach
    </div>
  @endif
  @if (!empty($message))
    <div class="alert alert-danger">{{ $message }}</div>  
  @endif
  <button type="submit" class="btn btn-primary">Submit</button>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
  </form>
</div>
</body>
</html>
