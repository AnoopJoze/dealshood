@extends('layouts.user_type.auth')

@section('content')

  <div class="row">

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Posts</p>
            <h5 class="font-weight-bolder mb-0">
              {{ number_format($totalPosts) }}
            </h5>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-collection text-lg opacity-10"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <p class="text-sm mb-0 text-capitalize font-weight-bold">Published Posts</p>
            <h5 class="font-weight-bolder mb-0 text-success">
              {{ number_format($publishedPosts) }}
            </h5>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
              <i class="ni ni-check-bold text-lg opacity-10"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <p class="text-sm mb-0 text-capitalize font-weight-bold">Draft Posts</p>
            <h5 class="font-weight-bolder mb-0 text-warning">
              {{ number_format($draftPosts) }}
            </h5>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
              <i class="ni ni-single-copy-04 text-lg opacity-10"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Views</p>
            <h5 class="font-weight-bolder mb-0">
              {{ number_format($totalViews) }}
            </h5>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
              <i class="ni ni-tv-2 text-lg opacity-10"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
  <div class="row">
  <div class="col-lg-8 mb-lg-0 mb-4">
  <div class="card">
    <div class="card-body p-3">
      <div class="row">
        <div class="col-lg-7">
          <div class="d-flex flex-column h-100">
            <p class="mb-1 pt-2 text-bold">Admin Dashboard</p>

            <h5 class="font-weight-bolder">
              Welcome back, {{ auth()->user()->name }}
            </h5>

            <p class="mb-5">
              Manage posts, users, categories, featured ads,
              media uploads and analytics from one place.
            </p>

            <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto"
              href="{{ route('posts.index') }}">
              Manage Posts
              <i class="fas fa-arrow-right text-sm ms-1"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
          <img class="w-100"
            src="{{ asset('frontend/img/dashboard-posts.png') }}">
        </div>
      </div>
    </div>
  </div>
</div>
  <div class="col-lg-4">
  <div class="card h-100 bg-gradient-dark">
    <div class="card-body position-relative z-index-1 p-3">
      <h5 class="text-white font-weight-bolder mb-4">
        Featured Posts
      </h5>

      <h1 class="text-white">
        {{ $featuredPosts }}
      </h1>

      <p class="text-white">
        Posts currently highlighted on homepage and search listings.
      </p>

      <a class="text-white text-sm font-weight-bold mb-0 icon-move-right"
        href="{{ route('posts.index') }}">
        View Featured
        <i class="fas fa-arrow-right text-sm ms-1"></i>
      </a>
    </div>
  </div>
</div>
</div>

  <div class="row">&nbsp;</div>
  <br>
  <div class="row">
  <div class="card">
    <div class="card-header pb-0">
        <h6>Recent Posts</h6>
    </div>

    <div class="card-body px-0 pb-2">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">

                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($recentPosts as $post)

                    <tr>
                        <td class="ps-4">
                            {{ Str::limit($post->title, 40) }}
                        </td>

                        <td>
                            @if($post->status == 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </td>

                        <td>
                            {{ number_format($post->views) }}
                        </td>

                        <td>
                            {{ $post->created_at->format('d M Y') }}
                        </td>
                    </tr>

                    @endforeach

                </tbody>

            </table>
        </div>
    </div>
</div>
</div>

@endsection
@push('dashboard')
  <script>
    window.onload = function() {
      var ctx = document.getElementById("chart-bars").getContext("2d");

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
          datasets: [{
            label: "Sales",
            tension: 0.4,
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            backgroundColor: "#fff",
            data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
            maxBarThickness: 6
          }, ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
              },
              ticks: {
                suggestedMin: 0,
                suggestedMax: 500,
                beginAtZero: true,
                padding: 15,
                font: {
                  size: 14,
                  family: "Open Sans",
                  style: 'normal',
                  lineHeight: 2
                },
                color: "#fff"
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false
              },
              ticks: {
                display: false
              },
            },
          },
        },
      });


      var ctx2 = document.getElementById("chart-line").getContext("2d");

      var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

      gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
      gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
      gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

      var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

      gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
      gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
      gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

      new Chart(ctx2, {
        type: "line",
        data: {
          labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
          datasets: [{
              label: "Mobile apps",
              tension: 0.4,
              borderWidth: 0,
              pointRadius: 0,
              borderColor: "#cb0c9f",
              borderWidth: 3,
              backgroundColor: gradientStroke1,
              fill: true,
              data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
              maxBarThickness: 6

            },
            {
              label: "Websites",
              tension: 0.4,
              borderWidth: 0,
              pointRadius: 0,
              borderColor: "#3A416F",
              borderWidth: 3,
              backgroundColor: gradientStroke2,
              fill: true,
              data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
              maxBarThickness: 6
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                padding: 10,
                color: '#b2b9bf',
                font: {
                  size: 11,
                  family: "Open Sans",
                  style: 'normal',
                  lineHeight: 2
                },
              }
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                color: '#b2b9bf',
                padding: 20,
                font: {
                  size: 11,
                  family: "Open Sans",
                  style: 'normal',
                  lineHeight: 2
                },
              }
            },
          },
        },
      });
    }
    new Chart(ctx2, {
    type: "line",
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: "Posts Created",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 3,
            borderColor: "#5e72e4",
            fill: true,
            data: @json($chartData),
        }],
    },
});
  </script>
@endpush

