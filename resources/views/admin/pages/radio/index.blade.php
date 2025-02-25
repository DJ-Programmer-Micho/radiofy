
@extends('admin.layout.layout')
@section('admin-content')
<div>
    @livewire('admin.radio-livewire')
</div>
@endsection
@push('super_script')
<script>
    window.addEventListener('close-modal', event => {
        $('#addRadioModal').modal('hide');
        $('#updateRadioModal').modal('hide');
        $('#deleteRadioModal').modal('hide');
    })

    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('addRadioModal'), {
            backdrop: 'static',  // Prevent closing when clicking outside
            keyboard: false      // Prevent closing on escape key press
        });
        new bootstrap.Modal(document.getElementById('updateRadioModal'), {
            backdrop: 'static',  // Prevent closing when clicking outside
            keyboard: false      // Prevent closing on escape key press
        });
        new bootstrap.Modal(document.getElementById('deleteRadioModal'), {
            backdrop: 'static',  // Prevent closing when clicking outside
            keyboard: false      // Prevent closing on escape key press
        });
    });
</script>
@endpush