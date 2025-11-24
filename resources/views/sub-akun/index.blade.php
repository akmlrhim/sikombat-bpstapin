@extends('layouts.template')
@section('content')
  <div class="row">

    {{-- button  --}}
    <div class="col-12">
      <a href="{{ route('sub-akun.create') }}" class="btn btn-success mb-3">Tambah Sub Akun</a>
    </div>

    <div class="col-12">
      <div class="card">

        <div class="card-header">
          <h3 class="card-title">Data</h3>

          <div class="card-tools">
            <div class="input-group input-group-sm" style="width: 210px;">
              <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
            </div>
          </div>
        </div>

        <div class="card-body table-responsive p-0">
          <table class="table table-bordered table-sm text-nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>Kode Akun Utama</th>
                <th>Kode Sub Akun</th>
                <th>Nama Sub Akun</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($subAkun as $index => $row)
                <tr>
                  <td>{{ $index + $subAkun->firstItem() }}</td>
                  <td>{{ $row->akun->kode_akun }}</td>
                  <td>{{ $row->kode_sub_akun }}</td>
                  <td>{{ $row->nama_sub_akun }}</td>
                  <td>
                    <a href="{{ route('sub-akun.show', $row->uuid) }}" class="btn btn-success btn-sm">Detail</a>
                    <a href="{{ route('sub-akun.edit', $row->uuid) }}" class="btn btn-info btn-sm">Edit</a>
                    <x-confirm-delete action="{{ route('sub-akun.destroy', $row->uuid) }}" />
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">Tidak ada data dalam tabel</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-center align-items-center text-sm">
      {{ $subAkun->links() }}
    </div>
  </div>
@endsection
