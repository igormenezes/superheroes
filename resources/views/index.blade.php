<!DOCTYPE html>
<html>
<head>
@include('layout/head')
</head>
<body>
@include('layout/nav')
<div class="container">
<ul class="list-unstyled">
    @foreach($superheroes as $hero)
    <li class="media box-superheroes-show">
        <img class="mr-3 superheroes-thumbnail" src="{{$hero->images[0]->file}}">
        <div class="media-body">
        <h5 class="mt-0 mb-1">{{$hero->nickname}}</h5>
        <a href='/details/{{$hero->id}}'>View details</a> <br />
        <a href='/edit/{{$hero->id}}'>Edit</a> <br />
        <a href='/remove/{{$hero->id}}'>Delete</a> <br />
        </div>
    </li>
    @endforeach
    {{ $superheroes->links() }}
</ul>
</div>
</body>
</html>
