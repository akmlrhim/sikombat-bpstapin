@extends('layouts.template')
@section('content')
  <div class="row">

    {{-- button  --}}
    <div class="col-12">
      <a href="{{ route('akun.create') }}" class="btn btn-success mb-3">Tambah Akun</a>
    </div>

    {{-- flashdata --}}
    <x-alert />


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
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Sub Akun</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($akun as $index => $row)
                <tr>
                  <td>{{ $index + $akun->firstItem() }}</td>
                  <td>{{ $row->kode_akun }}</td>
                  <td>{{ $row->nama_akun }}</td>
                  <td>
                    <a href="{{ route('akun.sub-akun', $row->uuid) }}" class="btn btn-success btn-sm">Sub Akun</a>
                  </td>
                  <td>
                    <a href="{{ route('akun.edit', $row->uuid) }}" class="btn btn-info btn-sm">Edit</a>
                    <x-confirm-delete action="{{ route('akun.destroy', $row->id) }}" />
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

    {{ $akun->links() }}
  </div>
@endsection
