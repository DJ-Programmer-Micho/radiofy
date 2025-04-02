
@extends('listener.layout.layout')
@section('listener-content')
<div>
    @livewire('listener.radio.radio-index-livewire',['slug' => $radio_id])
</div>
@endsection