
@extends('listener.layout.layout')
@section('listener-content')
<div>
    @livewire('listener.genre.genre-index-livewire',['id' => $genre_id])
</div>
@endsection