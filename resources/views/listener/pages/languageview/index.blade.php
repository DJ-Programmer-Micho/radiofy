
@extends('listener.layout.layout')
@section('listener-content')
<div>
    @livewire('listener.language.language-index-livewire',['code' => $code])
</div>
@endsection