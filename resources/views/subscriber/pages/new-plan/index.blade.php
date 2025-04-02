
@extends('subscriber.layout.layout')
@section('subscriber-content')
<div>
    <div class="page-content">
        <style>
            .m-index{
                z-index: 9999;
            }
        </style>

        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ __('Radios') }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Dashboard') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('Radios') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    @livewire('subscriber.plan.new-plan-livewire')
            <!--end col-->
        </div>
        <!--end row-->

    </div>
</div>

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