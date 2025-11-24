@extends('layouts.template')

@section('content')
  <div class="row">

    <div class="mb-3 col-12">
      <a href="{{ route('akun.index') }}" class="btn btn-secondary">Kembali</a>
      <a href="{{ route('akun.sub-akun.create', $akun->uuid) }}" class="btn btn-primary">
        Buat Sub Akun
      </a>
    </div>

    <x-alert />

    <div class="col-md-12">

      <h4 class="mb-3 text-primary">
        <strong>{{ $akun->nama_akun }}</strong>
        <span class="text-muted">({{ $akun->kode_akun }})</span>
      </h4>

      <div class="card">
        <div class="card-body table-responsive p-0">
          <table class="table table-sm table-bordered text-nowrap">
            <thead>
              <tr>
                <th>Kode Sub Akun</th>
                <th>Nama Sub Akun</th>
                <th>Total</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($akun->subAkun as $sub)
                <tr>
                  <td>{{ $sub->kode_sub_akun }}</td>
                  <td>{{ $sub->nama_sub_akun }}</td>
                  <td>Rp {{ number_format($sub->kegiatan->sum('total_harga'), 0, ',', '.') }}</td>
                  <td>
                    <a href="{{ route('akun.sub-akun.show', [$akun->uuid, $sub->uuid]) }}"
                      class="btn btn-sm btn-secondary">
                      Detail
                    </a>
                    <a href="{{ route('akun.sub-akun.edit', [$akun->uuid, $sub->uuid]) }}" class="btn btn-sm btn-warning">
                      Edit
                    </a>
                    <x-confirm-delete
                      action="{{ route('akun.sub-akun.destroy', [$akun->uuid, $sub->uuid]) }}"></x-confirm-delete>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada sub akun</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
