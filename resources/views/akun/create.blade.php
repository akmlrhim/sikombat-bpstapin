@extends('layouts.template')

@section('content')
  <div class="col-md-12">
    <div class="card card-primary">

      <div class="card-body">
        <form method="POST" action="{{ route('akun.store') }}">
          @csrf
          <div class="row">
            <div class="col-sm-12">
              <h3>Akun Utama</h3>
              <div class="form-group">
                <label for="kode_akun" class="text-sm">Kode Akun</label>
                <input type="text" class="form-control form-control-sm @error('kode_akun') is-invalid @enderror"
                  id="kode_akun" name="kode_akun" placeholder="Contoh. 2988" value="{{ old('kode_akun') }}">
                @error('kode_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama_akun" class="text-sm">Nama Akun</label>
                <input type="text" class="form-control form-control-sm @error('nama_akun') is-invalid @enderror"
                  id="nama_akun" name="nama_akun" placeholder="Contoh. Penyediaan dan Pengembangan ..."
                  value="{{ old('nama_akun') }}">
                @error('nama_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="pagu_anggaran" class="text-sm">Pagu Anggaran</label>
                <input type="number" class="form-control form-control-sm @error('pagu_anggaran') is-invalid @enderror"
                  id="pagu_anggaran" name="pagu_anggaran" placeholder="Masukkan Pagu Anggaran"
                  value="{{ old('pagu_anggaran') }}">
                @error('pagu_anggaran')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="sisa_anggaran" class="text-sm">Sisa Anggaran</label>
                <input type="number" class="form-control form-control-sm @error('sisa_anggaran') is-invalid @enderror"
                  id="sisa_anggaran" name="sisa_anggaran" placeholder="Masukkan Sisa Anggaran"
                  value="{{ old('sisa_anggaran') }}">
                @error('sisa_anggaran')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <hr>

              {{-- SUB AKUN SECTION  --}}
              <h4 class="text-primary font-weight-bold">Sub Akun</h4>

              <div class="form-group">
                <label for="kode_sub_akun" class="text-sm">Kode Sub Akun</label>
                <input type="text" name="sub_akun[0][kode_sub_akun]"
                  class="form-control form-control-sm @error('sub_akun.0.kode_sub_akun') is-invalid @enderror"
                  placeholder="Contoh. 2898.BMA.007" value="{{ old('sub_akun.0.kode_sub_akun') }}">
                @error('sub_akun.0.kode_sub_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama_sub_akun" class="text-sm">Nama Sub Akun</label>
                <input type="text" name="sub_akun[0][nama_sub_akun]"
                  class="form-control form-control-sm @error('sub_akun.0.nama_sub_akun') is-invalid @enderror"
                  placeholder="Contoh. PUBLIKASI/LAPORAN STATISTIK NERACA PENGELUARAN"
                  value="{{ old('sub_akun.0.nama_sub_akun') }}">
                @error('sub_akun.0.nama_sub_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama_kegiatan_sub_akun" class="text-sm">Nama Kegiatan Sub Akun</label>
                <input type="text" name="sub_akun[0][nama_kegiatan_sub_akun]"
                  class="form-control form-control-sm @error('sub_akun.0.nama_kegiatan_sub_akun') is-invalid @enderror"
                  placeholder="Contoh. Belanja Honor Output Kegiatan"
                  value="{{ old('sub_akun.0.nama_kegiatan_sub_akun') }}">
                @error('sub_akun.0.nama_kegiatan_sub_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- KEGIATAN SECTION  --}}
              <h5 class="text-primary font-weight-bold">Kegiatan</h5>
              <div id="kegiatan-container">
                @php
                  $oldKegiatan = old('sub_akun.0.kegiatan', [
                      [
                          'kode_akun_kegiatan' => '',
                          'nama_kegiatan' => '',
                          'jumlah_sampel' => '',
                          'satuan' => '',
                          'harga_satuan' => '',
                      ],
                  ]);
                @endphp

                @foreach ($oldKegiatan as $index => $kegiatan)
                  <div class="kegiatan-item border rounded p-3 mb-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <strong>Kegiatan #{{ $index + 1 }}</strong>
                      <button type="button" class="btn btn-danger btn-sm"
                        onclick="this.parentElement.parentElement.remove()">Hapus</button>
                    </div>

                    <div class="row g-2">
                      <div class="col-md-3">
                        <input type="text" name="sub_akun[0][kegiatan][{{ $index }}][kode_akun_kegiatan]"
                          class="form-control form-control-sm @error('sub_akun.0.kegiatan.' . $index . '.kode_akun_kegiatan') is-invalid @enderror"
                          placeholder="Contoh. 521214"
                          value="{{ old('sub_akun.0.kegiatan.' . $index . '.kode_akun_kegiatan') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.kode_akun_kegiatan')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>

                      <div class="col-md-4">
                        <input type="text" name="sub_akun[0][kegiatan][{{ $index }}][nama_kegiatan]"
                          class="form-control form-control-sm @error('sub_akun.0.kegiatan.' . $index . '.nama_kegiatan') is-invalid @enderror"
                          placeholder="Nama kegiatan"
                          value="{{ old('sub_akun.0.kegiatan.' . $index . '.nama_kegiatan') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.nama_kegiatan')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>

                      <div class="col-md-2">
                        <input type="number" name="sub_akun[0][kegiatan][{{ $index }}][jumlah_sampel]"
                          class="form-control form-control-sm @error('sub_akun.0.kegiatan.' . $index . '.jumlah_sampel') is-invalid @enderror"
                          placeholder="Jumlah" value="{{ old('sub_akun.0.kegiatan.' . $index . '.jumlah_sampel') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.jumlah_sampel')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>

                      <div class="col-md-1">
                        <input type="text" name="sub_akun[0][kegiatan][{{ $index }}][satuan]"
                          class="form-control form-control-sm @error('sub_akun.0.kegiatan.' . $index . '.satuan') is-invalid @enderror"
                          placeholder="Sat." value="{{ old('sub_akun.0.kegiatan.' . $index . '.satuan') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.satuan')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>

                      <div class="col-md-2">
                        <input type="number" name="sub_akun[0][kegiatan][{{ $index }}][harga_satuan]"
                          class="form-control form-control-sm @error('sub_akun.0.kegiatan.' . $index . '.harga_satuan') is-invalid @enderror"
                          placeholder="Harga" value="{{ old('sub_akun.0.kegiatan.' . $index . '.harga_satuan') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.harga_satuan')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <button type="button" class="btn btn-sm btn-success" onclick="addKegiatan()">+ Tambah Kegiatan</button>
            </div>
          </div>

          <div class="mt-2">
            <a href="{{ route('akun.index') }}">
              <button type="button" class="btn btn-sm btn-secondary">Kembali</button>
            </a>
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

<script>
  let kegiatanIndex = {{ count(old('sub_akun.0.kegiatan', [['dummy' => true]])) - 1 }};

  function addKegiatan() {
    kegiatanIndex++;
    const container = document.getElementById('kegiatan-container');
    const div = document.createElement('div');
    div.classList.add('kegiatan-item', 'border', 'rounded', 'p-3', 'mb-3', 'bg-light');

    div.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>Kegiatan #${kegiatanIndex + 1}</strong>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">Hapus</button>
      </div>
      <div class="row g-2">
        <div class="col-md-3">
          <input type="text" name="sub_akun[0][kegiatan][${kegiatanIndex}][kode_akun_kegiatan]" class="form-control form-control-sm" placeholder="Kode akun">
        </div>
        <div class="col-md-4">
          <input type="text" name="sub_akun[0][kegiatan][${kegiatanIndex}][nama_kegiatan]" class="form-control form-control-sm" placeholder="Nama kegiatan">
        </div>
        <div class="col-md-2">
          <input type="number" name="sub_akun[0][kegiatan][${kegiatanIndex}][jumlah_sampel]" class="form-control form-control-sm" placeholder="Jumlah">
        </div>
        <div class="col-md-1">
          <input type="text" name="sub_akun[0][kegiatan][${kegiatanIndex}][satuan]" class="form-control form-control-sm" placeholder="Sat.">
        </div>
        <div class="col-md-2">
          <input type="number" name="sub_akun[0][kegiatan][${kegiatanIndex}][harga_satuan]" class="form-control form-control-sm" placeholder="Harga">
        </div>
      </div>
    `;

    container.appendChild(div);
  }
</script>
