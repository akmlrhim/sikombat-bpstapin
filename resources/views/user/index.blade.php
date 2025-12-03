@extends('layouts.template')
@section('content')
  <div class="row">

    {{-- button  --}}
    <div class="col-12">
      <a href="{{ route('user.create') }}" class="btn btn-success mb-3">Tambah user</a>
    </div>

    <div class="col-12">
      <form action="{{ route('user.index') }}" method="GET">
        <div class="card shadow-sm rounded-lg">
          <div class="card-body">
            <div class="form-row">
              <div class="col-md-3 mb-3">
                <label for="role" class="font-weight-bold text-secondary">Role</label>
                <select name="role" id="role" class="custom-select" onchange="this.form.submit()">
                  <option value="">-- Semua Role --</option>
                  <option value="ketua_tim" {{ request('role') == 'ketua_tim' ? 'selected' : '' }}>Ketua Tim</option>
                  <option value="umum" {{ request('role') == 'umum' ? 'selected' : '' }}>Umum</option>
                  <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
              </div>

              <div class="col-md-5 mb-3">
                <label for="keyword" class="font-weight-bold text-secondary">Cari Nama / NIP</label>
                <input type="text" name="keyword" id="keyword" class="form-control" value="{{ request('keyword') }}"
                  placeholder="Masukkan Nama Lengkap atau NIP" autocomplete="off">
              </div>

              <div class="col-md-4 mb-3 d-flex align-items-end">
                <a id="reset_filter" href="{{ route('user.index') }}" class="btn btn-secondary btn-block">
                  Reset Filter
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
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>NIP</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($user as $index => $row)
                <tr>
                  <td>{{ $index + $user->firstItem() }}</td>
                  <td>{{ $row->nama_lengkap }}</td>
                  <td>{{ $row->email }}</td>
                  <td>{{ $row->nip }}</td>
                  <td>
                    {!! match ($row->role) {
                        'ketua_tim' => '<span class="badge badge-primary">Ketua Tim</span>',
                        'umum' => '<span class="badge badge-success">Umum</span>',
                        'user' => '<span class="badge badge-info">User</span>',
                    } !!}

                  </td>
                  <td>
                    <a href="{{ route('user.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <x-confirm-delete action="{{ route('user.destroy', $row->id) }}" />
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
        {{ $user->links() }}
      </div>
    </div>
  </div>
@endsection
