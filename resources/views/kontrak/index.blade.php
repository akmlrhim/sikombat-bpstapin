@extends('layouts.template')
@section('content')
  <div class="row">

    {{-- button  --}}
    <div class="col-12">
      <a href="{{ route('kontrak.create') }}" class="btn btn-success mb-3">Tambah kontrak</a>
    </div>

    <div class="col-12">
      <form action="{{ route('kontrak.index') }}" method="GET">
        <div class="card shadow-sm rounded-lg">
          <div class="card-body">
            <div class="form-row">
              <div class="col-md-3 mb-3">
                <label for="periode" class="font-weight-bold text-secondary">Periode</label>
                <input type="month" name="periode" id="periode" class="form-control" onclick="this.showPicker()"
                  onchange="this.form.submit()" value="{{ request('periode') }}" />
                </select>
              </div>

              <div class="col-md-5 mb-3">
                <label for="keyword" class="font-weight-bold text-secondary">Cari Nama Mitra</label>
                <input type="text" name="keyword" id="keyword" class="form-control" value="{{ request('keyword') }}"
                  placeholder="Masukkan Nama lengkap Mitra" />
              </div>

              <div class="col-md-2 mb-3 d-flex align-items-end">
                <a href="{{ route('kontrak.index') }}" class="btn btn-secondary btn-block">
                  Reset Filter
                </a>
              </div>
              <div class="col-md-2 mb-3 d-flex align-items-end">
                <a href="{{ route('user.index') }}" class="btn btn-danger btn-block">
                  Export Data
                </a>
              </div>

            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body table-responsive p-0">
          <table class="table table-bordered table-sm text-nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>NMS</th>
                <th>Nama Mitra</th>
                <th>Periode</th>
                <th>Total Honor</th>
                <th>File Kontrak</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($kontrak as $index => $row)
                <tr>
                  <td>{{ $index + $kontrak->firstItem() }}</td>
                  <td>{{ $row->mitra->nms }}</td>
                  <td>{{ $row->mitra->nama_lengkap }}</td>
                  <td>{{ $row->periode->translatedFormat('F Y') }}</td>
                  <td>Rp {{ number_format($row->total_honor, 0, ',', '.') }}</td>
                  <td>
                    <a href="{{ route('kontrak.file', $row->uuid) }}" class="btn btn-secondary btn-sm"
                      title="Unduh File Kontrak" target="_blank">
                      Unduh File
                    </a>
                  </td>
                  <td>
                    <a href="{{ route('kontrak.show', $row->uuid) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('kontrak.edit', $row->uuid) }}" class="btn btn-warning btn-sm">Edit</a>
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
      </div>

      <div class="d-flex justify-content-center align-items-center text-sm">
        {{ $kontrak->links() }}
      </div>
    </div>
  </div>
@endsection
