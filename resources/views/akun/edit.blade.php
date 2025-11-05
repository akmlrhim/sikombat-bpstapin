@extends('layouts.template')

@section('content')
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-body">
        <form method="POST" action="{{ route('akun.update', $akun->uuid) }}">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-sm-12">
              <h3>Edit Akun Utama</h3>

              {{-- Akun Utama --}}
              <div class="form-group">
                <label class="text-sm">Kode Akun</label>
                <input type="text" name="kode_akun"
                  class="form-control form-control-sm @error('kode_akun') is-invalid @enderror"
                  value="{{ old('kode_akun', $akun->kode_akun) }}">
                @error('kode_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label class="text-sm">Nama Akun</label>
                <input type="text" name="nama_akun"
                  class="form-control form-control-sm @error('nama_akun') is-invalid @enderror"
                  value="{{ old('nama_akun', $akun->nama_akun) }}">
                @error('nama_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label class="text-sm">Pagu Anggaran</label>
                <input type="number" name="pagu_anggaran"
                  class="form-control form-control-sm @error('pagu_anggaran') is-invalid @enderror"
                  value="{{ old('pagu_anggaran', $akun->pagu_anggaran) }}">
                @error('pagu_anggaran')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label class="text-sm">Sisa Anggaran</label>
                <input type="number" name="sisa_anggaran"
                  class="form-control form-control-sm @error('sisa_anggaran') is-invalid @enderror"
                  value="{{ old('sisa_anggaran', $akun->sisa_anggaran) }}">
                @error('sisa_anggaran')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <hr>

              {{-- SUB AKUN --}}
              <h4 class="text-primary font-weight-bold">Sub Akun</h4>
              @php $sub = $akun->subAkun->first(); @endphp

              <div class="form-group">
                <label class="text-sm">Kode Sub Akun</label>
                <input type="text" name="sub_akun[kode_sub_akun]"
                  class="form-control form-control-sm @error('sub_akun.kode_sub_akun') is-invalid @enderror"
                  value="{{ old('sub_akun.kode_sub_akun', $sub->kode_sub_akun ?? '') }}">
                @error('sub_akun.kode_sub_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label class="text-sm">Nama Sub Akun</label>
                <input type="text" name="sub_akun[nama_sub_akun]"
                  class="form-control form-control-sm @error('sub_akun.nama_sub_akun') is-invalid @enderror"
                  value="{{ old('sub_akun.nama_sub_akun', $sub->nama_sub_akun ?? '') }}">
                @error('sub_akun.nama_sub_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label class="text-sm">Nama Kegiatan Sub Akun</label>
                <input type="text" name="sub_akun[nama_kegiatan_sub_akun]"
                  class="form-control form-control-sm @error('sub_akun.nama_kegiatan_sub_akun') is-invalid @enderror"
                  value="{{ old('sub_akun.nama_kegiatan_sub_akun', $sub->nama_kegiatan_sub_akun ?? '') }}">
                @error('sub_akun.nama_kegiatan_sub_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- KEGIATAN --}}
              <h5 class="text-primary font-weight-bold">Kegiatan</h5>
              <div id="kegiatan-container">
                @php
                  $oldKegiatan = old(
                      'sub_akun.kegiatan',
                      $sub->kegiatan->toArray() ?? [
                          [
                              'kode_akun_kegiatan' => '',
                              'nama_kegiatan' => '',
                              'jumlah_sampel' => '',
                              'satuan' => '',
                              'harga_satuan' => '',
                          ],
                      ],
                  );
                @endphp

                @foreach ($oldKegiatan as $i => $k)
                  <div class="kegiatan-item border rounded p-3 mb-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <strong>Kegiatan #{{ $i + 1 }}</strong>
                      <button type="button" class="btn btn-danger btn-sm"
                        onclick="this.closest('.kegiatan-item').remove()">Hapus</button>
                    </div>
                    <div class="row g-2">
                      <div class="col-md-3">
                        <input type="text" name="sub_akun[kegiatan][{{ $i }}][kode_akun_kegiatan]"
                          class="form-control form-control-sm" placeholder="Kode Akun"
                          value="{{ old("sub_akun.kegiatan.$i.kode_akun_kegiatan", $k['kode_akun_kegiatan'] ?? '') }}">
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="sub_akun[kegiatan][{{ $i }}][nama_kegiatan]"
                          class="form-control form-control-sm" placeholder="Nama Kegiatan"
                          value="{{ old("sub_akun.kegiatan.$i.nama_kegiatan", $k['nama_kegiatan'] ?? '') }}">
                      </div>
                      <div class="col-md-2">
                        <input type="number" name="sub_akun[kegiatan][{{ $i }}][jumlah_sampel]"
                          class="form-control form-control-sm" placeholder="Jumlah"
                          value="{{ old("sub_akun.kegiatan.$i.jumlah_sampel", $k['jumlah_sampel'] ?? '') }}">
                      </div>
                      <div class="col-md-1">
                        <input type="text" name="sub_akun[kegiatan][{{ $i }}][satuan]"
                          class="form-control form-control-sm" placeholder="Sat."
                          value="{{ old("sub_akun.kegiatan.$i.satuan", $k['satuan'] ?? '') }}">
                      </div>
                      <div class="col-md-2">
                        <input type="number" name="sub_akun[kegiatan][{{ $i }}][harga_satuan]"
                          class="form-control form-control-sm" placeholder="Harga"
                          value="{{ old("sub_akun.kegiatan.$i.harga_satuan", $k['harga_satuan'] ?? '') }}">
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <button type="button" class="btn btn-sm btn-success mt-2" onclick="addKegiatan()">+ Tambah
                Kegiatan</button>

            </div>
          </div>

          <div class="mt-3">
            <a href="{{ route('akun.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection

<script>
  let kegiatanIndex =
    {{ is_array(old('sub_akun.kegiatan', $sub->kegiatan->toArray() ?? [])) ? count(old('sub_akun.kegiatan', $sub->kegiatan->toArray() ?? [])) : 0 }};

  function addKegiatan() {
    const container = document.getElementById('kegiatan-container');
    const div = document.createElement('div');
    div.classList.add('kegiatan-item', 'border', 'rounded', 'p-3', 'mb-3', 'bg-light');
    div.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>Kegiatan #${kegiatanIndex + 1}</strong>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.kegiatan-item').remove()">Hapus</button>
      </div>
      <div class="row g-2">
        <div class="col-md-3"><input type="text" name="sub_akun[kegiatan][${kegiatanIndex}][kode_akun_kegiatan]" class="form-control form-control-sm" placeholder="Kode Akun"></div>
        <div class="col-md-4"><input type="text" name="sub_akun[kegiatan][${kegiatanIndex}][nama_kegiatan]" class="form-control form-control-sm" placeholder="Nama Kegiatan"></div>
        <div class="col-md-2"><input type="number" name="sub_akun[kegiatan][${kegiatanIndex}][jumlah_sampel]" class="form-control form-control-sm" placeholder="Jumlah"></div>
        <div class="col-md-1"><input type="text" name="sub_akun[kegiatan][${kegiatanIndex}][satuan]" class="form-control form-control-sm" placeholder="Sat."></div>
        <div class="col-md-2"><input type="number" name="sub_akun[kegiatan][${kegiatanIndex}][harga_satuan]" class="form-control form-control-sm" placeholder="Harga"></div>
      </div>
    `;
    container.appendChild(div);
    kegiatanIndex++;
  }
</script>
