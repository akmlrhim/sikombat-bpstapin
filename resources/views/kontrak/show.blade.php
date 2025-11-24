@extends('layouts.template')
@section('content')
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-body">

        {{-- data mitra  --}}
        <h4 class="text-primary m-0">Data Mitra</h4>
        <div class="table-responsive p-0">
          <table class="table table-sm table-bordered mb-4 text-nowrap">
            <tr>
              <th>NMS</th>
              <td>{{ $kontrak->mitra->nms }}</td>
            </tr>
            <tr>
              <th>Nama Lengkap</th>
              <td>{{ $kontrak->mitra->nama_lengkap }}</td>
            </tr>
            <tr>
              <th>Jenis Kelamin</th>
              <td>{{ $kontrak->mitra->jenis_kelamin }}</td>
            </tr>
            <tr>
              <th>Alamat</th>
              <td>{{ $kontrak->mitra->alamat }}</td>
            </tr>
          </table>
        </div>

        {{-- data kontrak  --}}
        <h4 class="text-primary m-0">Data Kontrak</h4>
        <div class="table-responsive p-0">
          <table class="table table-sm table-bordered mb-4 text-nowrap">
            <tr>
              <th>PERIODE</th>
              <td>{{ $kontrak->periode->translatedFormat('F Y') }}</td>
            </tr>
            <tr>
              <th>Tanggal Kontrak</th>
              <td>{{ $kontrak->tanggal_kontrak->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
              <th>Tanggal BAST</th>
              <td>{{ $kontrak->tanggal_bast->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
              <th>Tanggal Surat</th>
              <td>{{ $kontrak->tanggal_surat->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
              <th>Jadwal</th>
              <td>{{ $kontrak->tanggal_mulai->translatedFormat('d F Y') }} -
                {{ $kontrak->tanggal_berakhir->translatedFormat('d F Y') }}</td>
            </tr>
          </table>
        </div>

        {{-- data kegiatan mitra --}}
        <h4 class="text-primary mb-0">Data kegiatan</h4>
        <div class="table-responsive p-0">
          <table class="table-sm table-bordered table text-nowrap align-middle">
            <thead>
              <tr class="text-center">
                <th>Kode Akun</th>
                <th>Kegiatan</th>
                <th>
                  <div class="text-center fw-bold">Jumlah Dokumen</div>
                  <div class="d-flex justify-content-between mt-1 fw-normal">
                    <span class="text-danger">Target</span>
                    <span class="text-success">Dicapai</span>
                  </div>
                </th>
                <th>Harga satuan</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($kontrak->detail as $row)
                <tr>
                  <td>{{ $row->subAkun->kode_sub_akun }}.{{ $row->kegiatan->kode_akun_kegiatan }}</td>
                  <td>{{ $row->kegiatan->nama_kegiatan }}</td>

                  <td>
                    <div class="d-flex justify-content-between mt-1 fw-normal">
                      <span class="text-danger">{{ $row->jumlah_target_dokumen }}</span> |
                      <span class="text-success">{{ $row->jumlah_dokumen }}</span>
                    </div>
                  </td>

                  <td>Rp {{ number_format($row->kegiatan->harga_satuan, 0, ',', '.') }}</td>
                  <td>Rp {{ number_format($row->total_honor, 0, ',', '.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">Tidak ada data dalam tabel.</td>
                </tr>
              @endforelse
            </tbody>
            @if ($kontrak->detail->count() > 0)
              <tfoot class="font-weight-bold">
                <tr>
                  <td colspan="4" class="text-right">Jumlah Honor diterima</td>
                  <td>Rp {{ number_format($kontrak->detail->sum('total_honor'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                  <td colspan="4" class="text-right">Persentase Keberhasilan</td>
                  <td>
                    {{ number_format(
                        ($kontrak->detail->sum('jumlah_dokumen') / $kontrak->detail->sum('jumlah_target_dokumen')) * 100,
                        2,
                    ) }}%

                  </td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>

        <div class="mt-3">
          <a href="{{ route('kontrak.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

      </div>
    </div>
  </div>
@endsection
