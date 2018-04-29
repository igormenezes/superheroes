<!DOCTYPE html>
<html>
<head>
@include('layout/head')
</head>
<body>
@include('layout/nav')
<div class="container">
    <div id='all-box-images'>
        @foreach($hero->images as $image)
        <div class='box-image'>
            <img src="{{$image->file}}" class='img-thumbnail'>
        </div>
        @endforeach
    </div>
    <div id='details-hero'>
        <div class='box-hero'>
            <h3>Nickname:</h3><strong>{{ $hero->nickname }}</strong>
        </div>
        <div class='box-hero'>
            <h3>Real Name:</h3><strong>{{ $hero->real_name }}</strong>
        </div>
        <div class='box-hero'>
            <h3>Origin Description:</h3><strong>{{ $hero->origin_description }}</strong>
        </div>
        <div class='box-hero'>
            <h3>Super Powers:</h3><strong>{{ $hero->superpowers }}</strong>
        </div>
        <div class='box-hero'>
            <h3>Catch Phrase:</h3><strong>{{ $hero->catch_phrase }}</strong>
        </div>
    </div>
</div>
</body>
</html>
