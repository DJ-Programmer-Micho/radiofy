@extends('listener.auth.layout')
@section('listener-auth')
<div>
    @livewire('listener.auth.password-lis-livewire', [
        'token' => request('token'),
        'email' => request('email')
    ])
</div>
@endsection



