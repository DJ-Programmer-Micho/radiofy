
@extends('landing.layout.layout')
@section('landing-content')
<div>
    {{-- @livewire('landing.dashboard.dashboard-livewire') --}}
    @livewire('landing.nav-one-livewire')
    @livewire('landing.hero-one-livewire')
    {{-- @livewire('landing.slider-one-livewire') --}}
    @livewire('landing.top-radio-one-livewire')
    @livewire('landing.service-one-livewire')
    @livewire('landing.cta-one-livewire')
    @livewire('landing.features-one-livewire')
    {{-- @livewire('landing.plan-one-livewire') --}}
    <div style="max-width: 1600px" class="mx-auto">
        @livewire('subscriber.plan.new-plan-livewire')
    </div>
    @livewire('landing.faqs-one-livewire')
    @livewire('landing.contact-one-livewire')
    @livewire('landing.footer-one-livewire')
    
    
</div>
@endsection