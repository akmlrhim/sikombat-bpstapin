@extends('layouts.template')

@section('content')
  <div class="row">

    {{-- button  --}}
    <div class="col-12">
      <a href="{{ route('mitra.create') }}" class="btn btn-success mb-3">Tambah Mitra</a>
    </div>

    {{-- flashdata --}}
    <x-alert />

    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ $mitra->total() }} data</h3>

          <div class="card-tools">
            <form action="{{ route('mitra.index') }}" method="GET" style="width: 260px;">
              <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="Masukkan nama, lalu tekan Enter"
                  onchange="this.form.submit()" value="{{ request('search') }}" autocomplete="off">

                <div class="input-group-append">
                  <a href="{{ route('mitra.index') }}" class="btn btn-secondary">
                    Reset
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-bordered table-sm text-nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>NMS</th>
                <th>Nama Lengkap</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($mitra as $index => $row)
                <tr>
                  <td>{{ $index + $mitra->firstItem() }}</td>
                  <td>{{ $row->nms }}</td>
                  <td>{{ $row->nama_lengkap }}</td>
                  <td>{{ $row->jenis_kelamin }}</td>
                  <td>{{ $row->alamat }}</td>
                  <td>
                    <a href="{{ route('mitra.edit', $row->uuid) }}" class="btn btn-info btn-sm">Edit</a>
                    <x-confirm-delete action="{{ route('mitra.destroy', $row->uuid) }}" />
                  </td>
                </tr>

              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted text-sm">Tidak ada data dalam tabel</td>
                </tr>
              @endforelse
            </tbody>
          </table>

        </div>

        <div class="card-footer clearfix">
          <ul class="pagination m-0 float-right">
            {{ $mitra->links() }}
          </ul>
        </div>

      </div>
    </div>
  </div>
@endsection
