@extends('subscriber.auth.layout')
@section('subscriber-auth')
<div>
    @livewire('subscriber.auth.password-subs-livewire', [
        'token' => request('token'),
        'email' => request('email')
    ])
</div>
@endsection



