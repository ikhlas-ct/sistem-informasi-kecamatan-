{{-- @dd(Auth::user()->pegawai) --}}

@extends('layouts.user.user')
@section('tittle')
@section('content')

<div class="container">
    <div class="page-inner">
      <div
        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
      >
        <div>
          <h3 class="fw-bold mb-3">Dashboard</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
          <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
          <a href="#" class="btn btn-primary btn-round">Add Customer</a>
        </div>
      </div>

      @php
  use Illuminate\Support\Str;

  // Kumpulan statistik utama
  $stats = [
    ['title' => 'Pegawai',                 'icon' => 'fas fa-user-tie',            'value' => $pegawaiCount],
    ['title' => 'Masyarakat',              'icon' => 'fas fa-users',               'value' => $masyarakatCount],
    ['title' => 'Total Visitors',          'icon' => 'fas fa-eye',                 'value' => $totalVisitors],
    ['title' => 'Visitors Hari Ini',       'icon' => 'fas fa-clock',               'value' => $todayVisitors],
    ['title' => 'Pengaduan',               'icon' => 'fas fa-exclamation-triangle','value' => $totalPengaduan],
    ['title' => 'Surat Ket. Tidak Mampu',       'icon' => 'fas fa-file-alt',            'value' => $totalsuratketengan],
  ];

  // Statistik per jenis konten
  // pastikan semua jenis muncul walau jumlahnya nol
  $allJenis = ['berita','artikel','seni_tari','makanan_daerah','kerajinan_daerah','seni_musik','seni_budaya','pariwisata'];
  foreach ($allJenis as $j) {
      $kontenCounts[$j] = $kontenCounts[$j] ?? 0;
  }
@endphp
      <div class="row">
        {{-- Loop pertama: statistik utama --}}
        @foreach($stats as $stat)
          <div class="col-sm-6 col-md-3 mb-3">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                      <i class="{{ $stat['icon'] }}"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">{{ $stat['title'] }}</p>
                      <h4 class="card-title">{{ $stat['value'] }}</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach

        {{-- Loop kedua: statistik konten per jenis --}}
        @foreach($kontenCounts as $jenis => $count)
          <div class="col-sm-6 col-md-3 mb-3">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-success bubble-shadow-small">
                      <i class="fas fa-file-alt"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">
                        {{ Str::title(str_replace('_',' ',$jenis)) }}
                      </p>
                      <h4 class="card-title">{{ $count }}</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>


      <div class="row">
        <div class="col-md-12">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row">
                <div class="card-title">User Statistics</div>

              </div>
            </div>
            <div class="card-body">
              <div class="chart-container" style="min-height: 375px">
                <canvas id="statisticsChart"></canvas>
              </div>
              <div id="myChartLegend"></div>
            </div>
          </div>
        </div>

      </div>




    </div>
  </div>




@endsection

@section('scripts')

<script>
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    const statisticsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah Pengunjung',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(29, 122, 243, 0.2)',
                borderColor: '#1d7af3',
                borderWidth: 2,
                tension: 0.4,
                pointRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pengunjung'
                    },
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

@endsection
