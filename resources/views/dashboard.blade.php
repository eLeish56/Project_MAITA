<x-layout>
  <x-slot:title>Dashboard</x-slot:title>

  @if (session('status'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <b>{{ session('status') }}</b>
    </div>
  @endif

  <div class="row">
    <!-- Kartu: Total Barang -->
    <div class="col-md-4">
      <a href="{{ route('item.index') }}">
        <div class="card card-hover">
          <div class="box bg-danger">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">{{ $total_items }}</h2>
                <h6 class="text-white fw-normal">Total Barang</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-package-variant-closed" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- Kartu: Total Penjualan -->
    <div class="col-md-3">
      <a href="#">
        <div class="card card-hover">
          <div class="box bg-info">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">{{ $total_transactions }}</h2>
                <h6 class="text-white fw-normal">Total Penjualan</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-cart" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- Kartu: Total Pendapatan -->
    <div class="col-md-5">
      <a href="#">
        <div class="card card-hover">
          <div class="box bg-success">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">@indo_currency($total_income)</h2>
                <h6 class="text-white fw-normal">Total Pendapatan</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-cash-multiple" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <div class="row">
    <!-- Kartu: Pendapatan Hari Ini -->
    <div class="col-md-6">
      <a href="#">
        <div class="card card-hover">
          <div class="box bg-primary">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">@indo_currency($income_today)</h2>
                <h6 class="text-white fw-normal">Pendapatan Hari Ini</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-cash-multiple" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- Kartu: Pendapatan Bulan Ini -->
    <div class="col-md-6">
      <a href="#">
        <div class="card card-hover">
          <div class="box bg-cyan">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">Rp. 0</h2>
                <h6 class="text-white fw-normal">Pendapatan Bulan Ini</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-cash-multiple" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Kartu: Purchase Order -->
  @if (auth()->user()->role === 'supervisor' || auth()->user()->role === 'admin')
  <div class="row">
    <div class="col-md-12">
      <a href="{{ route('new-purchase-orders.index') }}">
        <div class="card card-hover">
          <div class="box bg-warning">
            <div class="row align-items-center">
              <div class="col-md-10">
                <h2 class="fw-bold text-white">Manajemen Purchase Order</h2>
                <h6 class="text-white fw-normal">Buat dan pantau PO ke supplier</h6>
              </div>
              <div class="col-md-2">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-file-document-edit" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
  @endif

  <!-- Grafik Pendapatan dan Penjualan -->
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header bg-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
              <i class="fas fa-chart-bar text-primary me-2"></i>
              Grafik Pendapatan dan Total Penjualan per Bulan
            </h5>
            <div class="btn-group">
              <button type="button" class="btn btn-outline-primary btn-sm" id="thisYear">
                Tahun Ini
              </button>
              <button type="button" class="btn btn-outline-primary btn-sm" id="lastYear">
                Tahun Lalu
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <canvas id="monthlyChart" style="height: 400px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Include Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
    // Format currency function
    function formatRupiah(value) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(value);
    }

    // Initialize chart
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [
          {
            label: 'Pendapatan',
            yAxisID: 'y-income',
            data: [],
            backgroundColor: 'rgba(40, 167, 69, 0.8)', // Hijau
            borderColor: 'rgb(40, 167, 69)',
            borderWidth: 1,
            borderRadius: 4,
            order: 2
          },
          {
            label: 'Total Penjualan',
            yAxisID: 'y-sales',
            data: [],
            backgroundColor: 'rgba(220, 53, 69, 0.8)', // Merah
            borderColor: 'rgb(220, 53, 69)',
            borderWidth: 1,
            borderRadius: 4,
            order: 1
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
            labels: {
              usePointStyle: true,
              padding: 15
            }
          },
          title: {
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function(context) {
                if (context.dataset.yAxisID === 'y-income') {
                  return 'Pendapatan: ' + formatRupiah(context.raw);
                } else {
                  return 'Jumlah Penjualan: ' + context.raw.toLocaleString('id-ID') + ' transaksi';
                }
              }
            }
          }
        },
        scales: {
          'y-income': {
            type: 'linear',
            display: true,
            position: 'left',
            beginAtZero: true,
            title: {
              display: true,
              text: 'Pendapatan (IDR)',
              font: { weight: 'bold' }
            },
            ticks: {
              callback: function(value) {
                return formatRupiah(value);
              }
            }
          },
          'y-sales': {
            type: 'linear',
            display: true,
            position: 'right',
            beginAtZero: true,
            title: {
              display: true,
              text: 'Jumlah Penjualan',
              font: { weight: 'bold' }
            },
            ticks: {
              color: 'rgb(220, 53, 69)',
              callback: function(value) {
                return value.toLocaleString('id-ID');
              }
            },
            grid: {
              drawOnChartArea: false
            }
          },
          x: {
            title: {
              display: true,
              text: 'Bulan',
              font: { weight: 'bold' }
            }
          }
        }
      }
    });

    // Fetch data function
    function fetchChartData(year) {
      fetch(`/api/monthly-income?year=${year}`)
        .then(response => response.json())
        .then(data => {
          console.log('Data received:', data); // Debug data
          if (data.income && data.sales) {
            monthlyChart.data.datasets[0].data = data.income;
            monthlyChart.data.datasets[1].data = data.sales;
            monthlyChart.options.plugins.title.text = `Tahun ${year}`;
            monthlyChart.update();
          }
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    }

    // Event handlers
    document.getElementById('thisYear').addEventListener('click', function() {
      this.classList.add('btn-primary');
      this.classList.remove('btn-outline-primary');
      document.getElementById('lastYear').classList.add('btn-outline-primary');
      document.getElementById('lastYear').classList.remove('btn-primary');
      fetchChartData(new Date().getFullYear());
    });

    document.getElementById('lastYear').addEventListener('click', function() {
      this.classList.add('btn-primary');
      this.classList.remove('btn-outline-primary');
      document.getElementById('thisYear').classList.add('btn-outline-primary');
      document.getElementById('thisYear').classList.remove('btn-primary');
      fetchChartData(new Date().getFullYear() - 1);
    });

    // Initial load
    fetchChartData(new Date().getFullYear());
  </script>


  </script>
</x-layout>
