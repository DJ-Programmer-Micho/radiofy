
@extends('listener.layout.layout')
@section('listener-content')
<div>
    @livewire('listener.radio.radio-index-livewire',['id' => $radio_id])
</div>
@endsection