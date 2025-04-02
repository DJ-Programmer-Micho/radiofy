<div class="page-content">
    <div class="container-fluid">
        <style>
            .row {
                --vz-gutter-x: 0.5rem;
                --vz-gutter-y: 0rem;
            }
            .card {
                margin-bottom: 0.5rem;
            }
            .genre-card:hover{
              transform: scale(1.03);
              transition: transform 0.2s ease-out;
            }
        </style>
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Library</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Library</a></li>
                            <li class="breadcrumb-item active">Library</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xxl-3">
                <!--end card-->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-card">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-medium">Follows</td>
                                        <td>{{ $followsCount }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Liked</td>
                                        <td>{{ $likesCount }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Listener Membership</td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">{{ $membership }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Join Date</td>
                                        <td>{{ $joinDate }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end table-->
                        </div>
                    </div>
                </div>
                
                <!--end card-->
                <div class="card mb-3">
                    <h6 class="p-3">More Genre's You May Like</h6>
                    <div class="row p-0">
                        @foreach ($genres as $genre)
                          <div class="genre-card col-xxl-6 col-lg-4 col-md-4 col-sm-4 col-6">
                            <a wire:navigate href="{{  route('listener.genreView',['genreId' => $genre->id]) }}">
                              <div class="card border-0 text-white"
                              style="
                                background-image: url('{{ asset('storage/'.$genre->image) }}');
                                background-size: cover;
                                background-position: center;
                                background-repeat: no-repeat;
                                height: 100px;
                              ">
                              <div class="card-body p-2 d-flex align-items-center">
                                <div class="flex-grow-1 d-flex align-items-start justify-content-start">
                                  <h5 class="mb-0">{{ $genre->genreTranslation->name }}</h5>
                                </div>
                              </div>
                            </div>
                            </a>
                          </div>
                        @endforeach
                      </div>
                </div>
                <!--end card-->

                <!--end card-->
            </div>
            <!---end col-->
            <div class="col-xxl-9">
                @livewire('listener.home.liked-radio-livewire')
                @livewire('listener.library.owner-radio-livewire')
                @livewire('listener.home.followed-radio-livewire')
                @livewire('listener.home.sponser-radio-livewire')
                @livewire('listener.home.discover-radio-livewire')
                <!--end card-->

                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div> 
</div><!-- End Page-content -->
@push('scripts')
<script>
    if (!window.turboAudioSwitchInitialized) {
    window.turboAudioSwitchInitialized = true;

    window.addEventListener('switch-radio', event => {
        const radioId = event.detail.radioId;
        console.log('Switch radio event received with ID:', radioId);
        // Now re-emit the Livewire event to trigger the switch in the persistent component
        Livewire.emit('playNowEvent', radioId);
    });
}

</script>
@endpush