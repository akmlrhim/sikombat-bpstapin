@extends('layouts.template')

@push('css-libs')
  <style>
    #visitsChart {
      width: 100% !important;
      height: 100% !important;
    }
  </style>
@endpush

@section('content')
  <div class="row align-items-stretch">
    <section class="col-md-8 d-flex">
      <div class="card flex-fill">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
              <span class="text-lg font-weight-bold">{{ count($visits) }} |</span>
              <span>Pengunjung Web</span>
            </h3>

            <form action="{{ route('home') }}" method="GET" class="mb-0">
              <select name="range" class="custom-select" onchange="this.form.submit()">
                <option value="1" {{ request('range') == 1 ? 'selected' : '' }}>Hari Ini</option>
                <option value="3" {{ request('range') == 3 ? 'selected' : '' }}>3 Hari Terakhir</option>
                <option value="7" {{ request('range') == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
                <option value="30" {{ request('range') == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
              </select>
            </form>
          </div>
        </div>

        <div class="card-body">
          <canvas id="visitsChart" height="300"></canvas>
        </div>
      </div>
    </section>

    <section class="col-md-4 d-flex">
      <div class="card flex-fill">
        <div class="card-header">
          <div class="card-title">
            <span>Pengunjung Berdasarkan Browser</span>
          </div>
        </div>
        <div class="card-body">
          <canvas id="browserChart" height="300"></canvas>
        </div>
      </div>
    </section>


    <section class="col-md-8 d-flex">
      <div class="card flex-fill">
        <div class="card-header border-transparent">
          <h3 class="card-title">Kontrak Baru</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table m-0 text-nowrap">
              <thead>
                <tr>
                  <th>Nama Mitra</th>
                  <th>Periode Kontrak</th>
                  <th>Jadwal Kontrak</th>
                  <th>Dibuat</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($kontrak as $k)
                  <tr>
                    <td>{{ $k->mitra->nama_lengkap }}</td>
                    <td>{{ $k->periode->translatedFormat('F Y') }}</td>
                    <td>{{ $k->tanggal_mulai->translatedFormat('d F Y') }} -
                      {{ $k->tanggal_berakhir->translatedFormat('d F Y') }} -
                    <td>
                      {{ $k->created_at->translatedFormat('d F Y') }} - {{ $k->created_at->translatedFormat('H:i') }}
                      <span class="text-muted text-sm d-block">{{ $k->created_at->diffForHumans() }}</span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer clearfix">
          <a href="{{ route('kontrak.create') }}" class="btn btn-sm btn-info float-left">Buat Kontrak Baru</a>
          <a href="{{ route('kontrak.index') }}" class="btn btn-sm btn-secondary float-right">Lihat Semua Kontrak</a>
        </div>
      </div>
    </section>

    <section class="col-md-4 d-flex">
      <div class="card flex-fill">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">
            Waktu Saat Ini
          </h5>
        </div>

        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center"
          style="min-height: 200px;">

          <div id="hari" class="h5 font-weight-bold text-primary mb-1"></div>

          <div id="tanggal" class="h6 text-muted mb-3"></div>

          <div id="waktu" class="display-4 font-weight-bold text-dark"></div>
        </div>
      </div>
    </section>

  </div>
@endsection

@section('scripts')
  <script src="{{ asset('js/chart.js') }}"></script>

  <script>
    function updateDateTime() {
      const now = new Date();

      const hari = [
        "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
      ];

      const bulan = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
      ];

      const namaHari = hari[now.getDay()];
      const tanggal = now.getDate();
      const namaBulan = bulan[now.getMonth()];
      const tahun = now.getFullYear();

      const jam = String(now.getHours()).padStart(2, '0');
      const menit = String(now.getMinutes()).padStart(2, '0');
      const detik = String(now.getSeconds()).padStart(2, '0');

      document.getElementById('hari').textContent = namaHari;
      document.getElementById('tanggal').textContent = `${tanggal} ${namaBulan} ${tahun}`;
      document.getElementById('waktu').textContent = `${jam}:${menit}:${detik}`;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
  </script>


  <script>
    const ctx = document.getElementById('visitsChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: {!! json_encode($visitsPerDay->pluck('date')) !!},
        datasets: [{
          label: 'Pengunjung',
          data: {!! json_encode($visitsPerDay->pluck('count')) !!},
          borderColor: 'blue',
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          fill: true,
          tension: 0.3,
          pointRadius: 4,
          pointBackgroundColor: 'rgba(54, 162, 235, 1)',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
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
              text: 'Jumlah Kunjungan'
            },
            ticks: {
              precision: 0
            }
          }
        }
      }
    });
  </script>

  <script>
    const browserData = @json($userBrowser);

    const browserLabels = Object.keys(browserData);
    const browserValues = Object.values(browserData);

    function getColor(index) {
      const colors = [
        "rgba(54, 162, 235, 0.7)",
        "rgba(255, 99, 132, 0.7)",
        "rgba(255, 206, 86, 0.7)",
        "rgba(75, 192, 192, 0.7)",
        "rgba(153, 102, 255, 0.7)",
        "rgba(255, 159, 64, 0.7)",
        "rgba(201, 203, 207, 0.7)",
        "rgba(100, 181, 246, 0.7)",
        "rgba(244, 67, 54, 0.7)",
        "rgba(255, 235, 59, 0.7)",
        "rgba(76, 175, 80, 0.7)",
        "rgba(121, 85, 72, 0.7)",
        "rgba(63, 81, 181, 0.7)",
        "rgba(0, 150, 136, 0.7)",
        "rgba(156, 39, 176, 0.7)"
      ];
      return colors[index % colors.length];
    }

    const backgroundColors = browserValues.map((_, i) => getColor(i));

    const ctxBrowser = document.getElementById('browserChart').getContext('2d');

    new Chart(ctxBrowser, {
      type: 'doughnut',
      data: {
        labels: browserLabels,
        datasets: [{
          data: browserValues,
          backgroundColor: backgroundColors,
          borderWidth: 1,
        }]
      },
      options: {
        responsive: true,
        cutoutPercentage: 60,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          }
        }
      }
    });
  </script>
@endsection
