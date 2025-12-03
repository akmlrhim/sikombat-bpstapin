@extends('layouts.template')

@section('content')
  <div class="row">

    <div class="mb-3 col-12">
      <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">&laquo; Kembali</a>
      <a href="{{ route('kegiatan.output.create', $kegiatan->uuid) }}" class="btn btn-success">
        &plus; Buat Output
      </a>
    </div>

    <div class="col-md-12">

      <h4 class="mb-3 text-primary">
        <strong>{{ $kegiatan->nama_kegiatan }}</strong>
        <span class="text-muted">({{ $kegiatan->kode_kegiatan }})</span>
      </h4>

      <div class="card">
        <div class="card-body table-responsive p-0">
          <table class="table table-sm table-bordered text-nowrap">
            <thead>
              <tr>
                <th>Kode Output</th>
                <th>Nama Output</th>
                <th>Total</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($kegiatan->output as $row)
                <tr>
                  <td>{{ $row->kode_output }}</td>
                  <td>{{ $row->nama_output }}</td>
                  <td>Rp {{ number_format($row->komponen->sum('total_harga'), 0, ',', '.') }}</td>
                  <td>
                    <a href="{{ route('kegiatan.output.show', [$kegiatan->uuid, $row->uuid]) }}"
                      class="btn btn-sm btn-secondary">
                      Detail
                    </a>
                    <a href="{{ route('kegiatan.output.edit', [$kegiatan->uuid, $row->uuid]) }}"
                      class="btn btn-sm btn-warning">
                      Edit
                    </a>
                    <x-confirm-delete
                      action="{{ route('kegiatan.output.destroy', [$kegiatan->uuid, $row->uuid]) }}"></x-confirm-delete>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada output</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
