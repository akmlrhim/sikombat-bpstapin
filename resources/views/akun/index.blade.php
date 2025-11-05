@extends('layouts.template')
@section('content')
  <div class="row">

    {{-- button  --}}
    <div class="col-12">
      <a href="{{ route('akun.create') }}" class="btn btn-sm btn-success mb-3">Tambah Akun</a>
    </div>

    {{-- flashdata --}}
    <x-alert />
    <div class="col-12">
      <div class="card">
        <div class="card-body table-responsive p-0">
          <table class="table table-bordered table-sm text-nowrap text-sm">
            <thead class="bg-success">
              <tr>
                <th>#</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Pagu Anggaran</th>
                <th>Sisa Anggaran</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($akun as $index => $row)
                <tr>
                  <td>{{ $index + $akun->firstItem() }}</td>
                  <td>{{ $row->kode_akun }}</td>
                  <td>{{ $row->nama_akun }}</td>
                  <td class="text-end">
                    {{ $row->pagu_anggaran ? 'Rp ' . number_format($row->pagu_anggaran, 0, ',', '.') : '-' }}
                  </td>
                  <td class="text-end">
                    {{ $row->sisa_anggaran ? 'Rp ' . number_format($row->sisa_anggaran, 0, ',', '.') : '-' }}
                  </td>

                  <td>
                    <a href="{{ route('akun.detail', $row->uuid) }}" class="btn btn-secondary btn-xs">Detail</a>
                    <a href="{{ route('akun.edit', $row->uuid) }}" class="btn btn-info btn-xs">Edit</a>
                    <x-confirm-delete action="{{ route('akun.destroy', $row->id) }}" />
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
      </div>
    </div>
    {{ $akun->links() }}
  </div>
@endsection
