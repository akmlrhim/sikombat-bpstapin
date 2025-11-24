@extends('layouts.template')
@section('content')
  <div class="row">

    {{-- button  --}}
    <div class="col-12">
      <a href="{{ route('kontrak.create') }}" class="btn btn-success mb-3">Tambah kontrak</a>
    </div>

    {{-- flashdata --}}
    <x-alert />

    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ $kontrak->total() }} data</h3>

          <div class="card-tools">
            <form action="{{ route('kontrak.index') }}" method="GET" style="width: 260px;">
              <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="Masukkan nama, lalu tekan Enter"
                  onchange="this.form.submit()" value="{{ request('search') }}" autocomplete="off">

                <div class="input-group-append">
                  <a href="{{ route('kontrak.index') }}" class="btn btn-secondary">
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
                <th>Nama Mitra</th>
                <th>Total Honor</th>
                <th>Periode</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($kontrak as $index => $row)
                <tr>
                  <td>{{ $index + $kontrak->firstItem() }}</td>
                  <td>{{ $row->mitra->nms }}</td>
                  <td>{{ $row->mitra->nama_lengkap }}</td>
                  <td>Rp {{ number_format($row->total_honor, 0, ',', '.') }}</td>
                  <td>{{ $row->periode->translatedFormat('F Y') }}</td>
                  <td>
                    <a href="{{ route('kontrak.show', $row->uuid) }}" class="btn btn-warning btn-sm">Detail</a>
                    <a href="{{ route('kontrak.edit', $row->uuid) }}" class="btn btn-info btn-sm">Edit</a>
                    <x-confirm-delete action="{{ route('kontrak.destroy', $row->uuid) }}" />
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

        <div class="card-footer clearfix">
          <ul class="pagination m-0 float-right">
            {{ $kontrak->links() }}
          </ul>
        </div>

      </div>
    </div>

  </div>
@endsection
