@extends('layouts.template')

@section('content')
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-body">

        <h3 class="text-primary mb-3">Akun Utama</h3>

        <table class="table table-sm table-bordered mb-4">
          <tr>
            <th width="25%">Kode Akun</th>
            <td>{{ $akun->kode_akun }}</td>
          </tr>
          <tr>
            <th>Nama Akun</th>
            <td>{{ $akun->nama_akun }}</td>
          </tr>
          <tr>
            <th>Pagu Anggaran</th>
            <td>Rp {{ number_format($akun->pagu_anggaran, 0, ',', '.') }}</td>
          </tr>
          <tr>
            <th>Sisa Anggaran</th>
            <td>Rp {{ number_format($akun->sisa_anggaran, 0, ',', '.') }}</td>
          </tr>
        </table>

        @php
          $sub = $akun->subAkun()->with('kegiatan')->first();
        @endphp

        @if ($sub)
          <h4 class="text-primary mt-4">Sub Akun</h4>
          <table class="table table-sm table-bordered mb-4">
            <tr>
              <th width="25%">Kode Sub Akun</th>
              <td>{{ $sub->kode_sub_akun }}</td>
            </tr>
            <tr>
              <th>Nama Sub Akun</th>
              <td>{{ $sub->nama_sub_akun }}</td>
            </tr>
            <tr>
              <th>Nama Kegiatan Sub Akun</th>
              <td>{{ $sub->nama_kegiatan_sub_akun }}</td>
            </tr>
          </table>

          {{-- Data Kegiatan --}}
          <h5 class="text-primary mt-3">Daftar Kegiatan</h5>

          <div class="table-responsive">
            <table class="table table-sm table-bordered">
              <thead>
                <tr>
                  <th width="5%">#</th>
                  <th>Kode Akun Kegiatan</th>
                  <th>Nama Kegiatan</th>
                  <th>Jumlah Sampel</th>
                  <th>Satuan</th>
                  <th>Harga Satuan</th>
                  <th>Total Harga</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($sub->kegiatan as $index => $keg)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $keg->kode_akun_kegiatan }}</td>
                    <td>{{ $keg->nama_kegiatan }}</td>
                    <td>{{ $keg->jumlah_sampel }}</td>
                    <td>{{ $keg->satuan }}</td>
                    <td>Rp {{ number_format($keg->harga_satuan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($keg->total_harga, 0, ',', '.') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada kegiatan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        @endif

        <div class="mt-3">
          <a href="{{ route('akun.index') }}" class="btn btn-secondary">Kembali</a>
          <a href="{{ route('akun.edit', $akun->uuid) }}" class="btn btn-primary">Edit Akun</a>
        </div>

      </div>
    </div>
  </div>
@endsection
