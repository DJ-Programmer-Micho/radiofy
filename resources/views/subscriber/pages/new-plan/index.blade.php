
@extends('subscriber.layout.layout')
@section('subscriber-content')
<div>
    @livewire('subscriber.plan.new-plan-livewire')

</div>
@endsection
@push('subscriber_script')
<script>
    window.addEventListener('close-modal', event => {
        $('#getRadioModal').modal('hide');

    })
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('getRadioModal'), {
            backdrop: 'static',
            keyboard: false
        });
    });
</script>
@endpush