<div class="page-content">
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

        <form wire:submit.prevent="updateServer">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{ __('Radio Server Information of:') }} {{ $radioName }}</h6>
                        </div>
                        <div class="card-body" >
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="address">{{__('BroadCast Type')}}</label>
                                        <input  type="text"  class="form-control" value="Icecast 1 & 2"  disabled>
                                        <small class="text-danger">FIXED</small>
                                    </div>
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="address">{{__('Address')}}</label>
                                        <div class="input-group">
                                            <input  type="text" id="addressInput" class="form-control" value="{{ env('APP_ADDRESS') }}"  disabled>
                                            <span class="input-group-text p-0"><button type="button" class="btn btn-link" onclick="copyAddress()">COPY</button></span>
                                        </div>
                                        <small class="text-danger">FIXED</small>
                                    </div>
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="port">{{__('Port')}}</label>
                                        <div class="input-group">
                                            <input  type="text" id="portInput" class="form-control" value="{{ env('APP_PORT') }}"  disabled>
                                            <span class="input-group-text p-0"><button type="button" class="btn btn-link" onclick="copyPort()">COPY</button></span>
                                        </div>
                                        <small class="text-danger">FIXED</small>
                                    </div>
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="mountPoint">{{__('Mount Point')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-2">/</span>
                                            <input type="text" id="mountInput" class="form-control" wire:model="radioNameSlug"  disabled>
                                            <span class="input-group-text p-0"><button type="button" class="btn btn-link" onclick="copyMount()">COPY</button></span>
                                        </div>
                                        <small class="text-info">Visit Radio Management in order to change</small>
                                        <small><a href="{{ route('subs-radio-manage',['radio_id' => $radio_id]) }}">LINK_<i class="fa-solid fa-up-right-from-square"></i></a></small>
                                    </div>
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="source">{{__('source')}}</label>
                                        <div class="input-group">
                                            
                                            <input type="text" id="source" class="form-control" wire:model="source">
                                            <span class="input-group-text px-2">@mradiofy</span>
                                            <span class="input-group-text p-0"><button type="button" class="btn btn-link" onclick="copySource()">COPY</button></span>
                                        </div>
                                        <small class="text-success">Source Used in (BUTT)</small>
                                    </div>
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="sourceSassword">{{__('Source Password')}}</label>
                                        <div class="input-group">
                                            <input  type="text" id="sourcePassword" class="form-control" wire:model="sourcePassword">
                                            <span class="input-group-text p-0"><button type="button" class="btn btn-link" onclick="copyPassword()">COPY</button></span>
                                        </div>
                                        <small class="text-success">Password used in (BUTT)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="mb-3">
                <a href="{{ route('subs-radios') }}" type="button" class="btn btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-danger submitJs">{{ __('Publish') }}</button>
            </div>
        </form>

    </div>
</div>

@push('radio_script')
<script>
    function copyAddress() {
        let input = document.getElementById("addressInput");
        input.select();
        input.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand("copy");
        alert("Address copied: " + input.value);
    }

    function copyPort() {
        let input = document.getElementById("portInput");
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Port copied: " + input.value);
    }

    function copyMount() {
        let input = document.getElementById("mountInput");
        let value = "/" + input.value;
        let temp = document.createElement("textarea");
        temp.value = value;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand("copy");
        document.body.removeChild(temp);
        alert("Mount point copied: " + value);
    }

    function copySource() {
        let input = document.getElementById("source");
        let value = input.value + '@mradiofy';
        let temp = document.createElement("textarea");
        temp.value = value;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand("copy");
        document.body.removeChild(temp);
        alert("Mount point copied: " + value);
    }

    function copyPassword() {
        let input = document.getElementById("sourcePassword");
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Port copied: " + input.value);
    }
</script>

@endpush