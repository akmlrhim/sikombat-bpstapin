@extends('layouts.template')

@section('content')
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-body">

        <h4 class="text-primary mb-2 font-weight-bold">Data Kegiatan</h4>
        <div class="table-responsive m-0">
          <table class="table table-sm mb-4 text-nowrap">
            <tr>
              <th width="25%" class="bg-primary">Kode Kegiatan</th>
              <td class="bg-primary font-weight-bold">{{ $output->kegiatan->kode_kegiatan }}</td>
            </tr>
            <tr>
              <th>Nama Kegiatan</th>
              <td>{{ $output->kegiatan->nama_kegiatan }}</td>
            </tr>
          </table>
        </div>

        <h4 class="text-primary mb-2 font-weight-bold">Data Output</h4>
        <div class="table-responsive m-0">
          <table class="table table-sm mb-4 text-nowrap">
            <tr>
              <th width="25%" class="bg-primary">Kode Output</th>
              <td class="bg-primary font-weight-bold">{{ $output->kode_output }}</td>
            </tr>
            <tr>
              <th>Nama Output</th>
              <td>{{ $output->nama_output }}</td>
            </tr>
            <tr>
              <th>Deskripsi</th>
              <td class="text-primary">{{ $output->deskripsi }}</td>
            </tr>
            <tr>
              <th>Total </th>
              <td class="font-weight-bold">Rp. {{ number_format($output->komponen->sum('total_harga'), 0, ',', '.') }}
              </td>
            </tr>
          </table>
        </div>

        <h4 class="text-primary mb-2 font-weight-bold">Daftar Kegiatan</h4>

        <div class="table-responsive">
          <table class="table table-sm table-bordered">
            <thead>
              <tr class="text-nowrap">
                <th class="text-primary">Kode Komponen</th>
                <th>Nama Komponen</th>
                <th>Jumlah Sampel</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
              </tr>
            </thead>
            <tbody class="text-nowrap">
              @forelse ($output->komponen as $kom)
                <tr>
                  <td class="text-primary font-weight-bold">{{ $kom->kode_komponen }}</td>
                  <td>{{ $kom->nama_komponen }}</td>
                  <td>{{ $kom->jumlah_sampel }}</td>
                  <td>{{ $kom->satuan }}</td>
                  <td>Rp {{ number_format($kom->harga_satuan, 0, ',', '.') }}</td>
                  <td>Rp {{ number_format($kom->total_harga, 0, ',', '.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted">Tidak ada kegiatan.</td>
                </tr>
              @endforelse
            </tbody>

            @if ($output->komponen->count() > 0)
              <tfoot class="text-nowrap">
                <tr>
                  <th colspan="5" class="text-right">Jumlah</th>
                  <th>
                    Rp {{ number_format($output->komponen->sum('total_harga'), 0, ',', '.') }}
                  </th>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
        <div class="mt-3">
          <a href="{{ route('kegiatan.output', $kegiatan->uuid) }}" class="btn btn-secondary">Kembali</a>
        </div>

      </div>
    </div>
  </div>
@endsection
