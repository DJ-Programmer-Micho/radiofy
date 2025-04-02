
@extends('subscriber.layout.layout')
@section('subscriber-content')
<div>
    @livewire('subscriber.promotion.my-promotion-livewire')
</div>
@endsection
@push('subscriber_script')
<script>
    window.addEventListener('close-modal', event => {
        $('#updatePromotionEditModal').modal('hide');
        $('#deletePromotionModal').modal('hide');
    })

    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('updatePromotionEditModal'), {
            backdrop: 'static', 
            keyboard: false     
        });
        new bootstrap.Modal(document.getElementById('deletePromotionModal'), {
            backdrop: 'static',  
            keyboard: false      
        });
    });
</script>
@endpush