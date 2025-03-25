<div class="pb-3">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <picture>
              <!-- Mobile image for screens 768px or less -->
              <source media="(max-width: 768px)" srcset="{{ asset('assets/banner/hero_mb_1.png') }}">
              <!-- Desktop image for screens larger than 768px -->
              <source media="(min-width: 769px)" srcset="{{ asset('assets/banner/hero_1.png') }}">
              <!-- Fallback image if none of the above matches -->
              <img src="{{ asset('assets/banner/hero_1.png') }}" alt="Radiofy Banner" width="100%">
            </picture>
          </div>
          <div class="carousel-item">
            <picture>
              <!-- Mobile image for screens 768px or less -->
              <source media="(max-width: 768px)" srcset="{{ asset('assets/banner/hero_mb_2.png') }}">
              <!-- Desktop image for screens larger than 768px -->
              <source media="(min-width: 769px)" srcset="{{ asset('assets/banner/hero_2.png') }}">
              <!-- Fallback image if none of the above matches -->
              <img src="{{ asset('assets/banner/hero_2.png') }}" alt="Radiofy Banner" width="100%">
            </picture>
          </div>
          <div class="carousel-item">
            <picture>
              <!-- Mobile image for screens 768px or less -->
              <source media="(max-width: 768px)" srcset="{{ asset('assets/banner/hero_mb_3.png') }}">
              <!-- Desktop image for screens larger than 768px -->
              <source media="(min-width: 769px)" srcset="{{ asset('assets/banner/hero_3.png') }}">
              <!-- Fallback image if none of the above matches -->
              <img src="{{ asset('assets/banner/hero_1.png') }}" alt="Radiofy Banner" width="100%">
            </picture>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
</div>