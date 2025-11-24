@extends('layouts.template')

@section('content')
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-body">

        <h4 class="text-primary m-0">Akun Utama</h4>
        <div class="table-responsive m-0">
          <table class="table table-sm mb-4 text-nowrap">
            <tr>
              <th width="25%" class="bg-primary">Kode Akun Utama</th>
              <td class="bg-primary font-weight-bold">{{ $subAkun->akun->kode_akun }}</td>
            </tr>
            <tr>
              <th>Nama Akun Utama</th>
              <td>{{ $subAkun->akun->nama_akun }}</td>
            </tr>
            <tr>
              <th>Pagu Anggaran</th>
              <td>Rp {{ number_format($subAkun->akun->pagu_anggaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <th>Sisa Anggaran</th>
              <td>Rp {{ number_format($subAkun->akun->sisa_anggaran, 0, ',', '.') }}</td>
            </tr>
          </table>
        </div>

        <h4 class="text-primary m-0">Sub Akun</h4>
        <div class="table-responsive m-0">
          <table class="table table-sm mb-4 text-nowrap">
            <tr>
              <th width="25%" class="bg-primary">Kode Akun Utama</th>
              <td class="bg-primary font-weight-bold">{{ $subAkun->kode_sub_akun }}</td>
            </tr>
            <tr>
              <th>Nama Akun Utama</th>
              <td>{{ $subAkun->nama_sub_akun }}</td>
            </tr>
            <tr>
              <th>Nama Kegiatan Sub Akun</th>
              <td class="text-primary">{{ $subAkun->nama_kegiatan_sub_akun }}</td>
            </tr>
            <tr>
              <th>Total </th>
              <td class="font-weight-bold">Rp. {{ number_format($subAkun->kegiatan->sum('total_harga'), 0, ',', '.') }}
              </td>
            </tr>
          </table>
        </div>

        <h5 class="text-primary">Daftar Kegiatan</h5>

        <div class="table-responsive">
          <table class="table table-sm table-bordered">
            <thead>
              <tr class="text-nowrap">
                <th class="text-primary">Kode Akun Kegiatan</th>
                <th>Nama Kegiatan</th>
                <th>Jumlah Sampel</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
              </tr>
            </thead>
            <tbody class="text-nowrap">
              @forelse ($subAkun->kegiatan as $keg)
                <tr>
                  <td class="text-primary font-weight-bold">{{ $keg->kode_akun_kegiatan }}</td>
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

            @if ($subAkun->kegiatan->count() > 0)
              <tfoot class="text-nowrap">
                <tr>
                  <th colspan="5" class="text-right">Jumlah</th>
                  <th>
                    Rp {{ number_format($subAkun->kegiatan->sum('total_harga'), 0, ',', '.') }}
                  </th>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
        <div class="mt-3">
          <a href="{{ route('akun.sub-akun', $akun->uuid) }}" class="btn btn-secondary">Kembali</a>
        </div>

      </div>
    </div>
  </div>
@endsection
