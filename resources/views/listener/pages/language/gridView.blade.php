<div class="page-content">
  <style>
    .row {
      --vz-gutter-x: 0.5rem;
      --vz-gutter-y: 0rem;
    }
    .card {
      margin-bottom: 0.5rem;
    }
    .genre-card:hover {
      transform: scale(1.03);
      transition: transform 0.2s ease-out;
    }
    .genre-card .img {
      position: relative;
    }
    .genre-card .img::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      /* background-color: rgba(0, 0, 0, 0.3); Dark overlay with 0.3 opacity */
    }
    .genre-card .card-body {
      position: relative; /* Ensure text is above the overlay */
      z-index: 1;
    }
  </style>
  @livewire('listener.home.main-ads-livewire')
  <div class="row">
    @foreach ($languages as $lang)
      <div class="genre-card col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6">
        <a wire:navigate href="{{ route('listener.languageView', ['code' => $lang->code]) }}">
          <div class="card img border-0 text-white"
            style="
              background-image: url('{{ asset('storage/' . $lang->image) }}');
              background-size: cover;
              background-position: center;
              background-repeat: no-repeat;
              height: 100px;
            ">
            <div class="card-body p-2 d-flex align-items-center">
              <div class="flex-grow-1 d-flex align-items-start justify-content-start">
                <h5 class="mb-0 text-white">{{ $lang->name }}</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>
  </div>