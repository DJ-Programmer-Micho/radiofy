<div>
    <div class="d-flex justify-content-start align-items-center p-0">
        <div>
            <h1 class="">Genre's You May be Intrested</h1>
        </div>
    </div>
    <hr>
    <div class="row">
        @foreach ($genres as $genre)
          <div class="genre-card col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6">
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
    <hr>
</div>