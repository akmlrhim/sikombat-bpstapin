@extends('layouts.template')

@section('content')
  <div class="col-md-12">
    <div class="card card-primary">

      <div class="card-body">
        <form method="POST" action="{{ route('akun.store') }}">
          @csrf
          <div class="row">
            <div class="col-sm-12">
              <h3 class="text-primary">Akun Utama</h3>
              <div class="form-group">
                <label for="kode_akun">Kode Akun</label>
                <input type="text" class="form-control @error('kode_akun') is-invalid @enderror" id="kode_akun"
                  name="kode_akun" placeholder="Contoh. 2988" value="{{ old('kode_akun') }}">
                @error('kode_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama_akun">Nama Akun</label>
                <input type="text" class="form-control @error('nama_akun') is-invalid @enderror" id="nama_akun"
                  name="nama_akun" placeholder="Contoh. Penyediaan dan Pengembangan ..." value="{{ old('nama_akun') }}">
                @error('nama_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="pagu_anggaran">Pagu Anggaran</label>
                <input type="text" inputmode="numeric"
                  class="form-control @error('pagu_anggaran') is-invalid @enderror" id="pagu_anggaran"
                  name="pagu_anggaran" placeholder="Masukkan Pagu Anggaran" value="{{ old('pagu_anggaran') }}">
                @error('pagu_anggaran')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <hr>

              {{-- SUB AKUN SECTION  --}}
              <h4 class="text-primary font-weight-bold">Sub Akun</h4>

              <div class="form-group">
                <label for="kode_sub_akun">Kode Sub Akun</label>
                <input type="text" name="sub_akun[0][kode_sub_akun]"
                  class="form-control @error('sub_akun.0.kode_sub_akun') is-invalid @enderror"
                  placeholder="Contoh. 2898.BMA.007" value="{{ old('sub_akun.0.kode_sub_akun') }}">
                @error('sub_akun.0.kode_sub_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama_sub_akun">Nama Sub Akun</label>
                <input type="text" name="sub_akun[0][nama_sub_akun]"
                  class="form-control @error('sub_akun.0.nama_sub_akun') is-invalid @enderror"
                  placeholder="Contoh. PUBLIKASI/LAPORAN STATISTIK NERACA PENGELUARAN"
                  value="{{ old('sub_akun.0.nama_sub_akun') }}">
                @error('sub_akun.0.nama_sub_akun')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama_kegiatan_sub_akun">Nama Kegiatan Sub Akun</label>
                <input type="text" name="sub_akun[0][nama_kegiatan_sub_akun]"
                  class="form-control @error('sub_akun.0.nama_kegiatan_sub_akun') is-invalid @enderror"
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
                      <div class="col-md-2">
                        <input type="text" name="sub_akun[0][kegiatan][{{ $index }}][kode_akun_kegiatan]"
                          class="form-control @error('sub_akun.0.kegiatan.' . $index . '.kode_akun_kegiatan') is-invalid @enderror"
                          placeholder="ex. 521214"
                          value="{{ old('sub_akun.0.kegiatan.' . $index . '.kode_akun_kegiatan') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.kode_akun_kegiatan')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>

                      <div class="col-md-6">
                        <input type="text" name="sub_akun[0][kegiatan][{{ $index }}][nama_kegiatan]"
                          class="form-control @error('sub_akun.0.kegiatan.' . $index . '.nama_kegiatan') is-invalid @enderror"
                          placeholder="Nama kegiatan"
                          value="{{ old('sub_akun.0.kegiatan.' . $index . '.nama_kegiatan') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.nama_kegiatan')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>

                      <div class="col-md-1">
                        <input type="number" name="sub_akun[0][kegiatan][{{ $index }}][jumlah_sampel]"
                          class="form-control @error('sub_akun.0.kegiatan.' . $index . '.jumlah_sampel') is-invalid @enderror"
                          placeholder="Jumlah" value="{{ old('sub_akun.0.kegiatan.' . $index . '.jumlah_sampel') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.jumlah_sampel')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>

                      <div class="col-md-1">
                        <input type="text" name="sub_akun[0][kegiatan][{{ $index }}][satuan]"
                          class="form-control @error('sub_akun.0.kegiatan.' . $index . '.satuan') is-invalid @enderror"
                          placeholder="Satuan" value="{{ old('sub_akun.0.kegiatan.' . $index . '.satuan') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.satuan')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>

                      <div class="col-md-2">
                        <input type="text" inputmode="numeric"
                          name="sub_akun[0][kegiatan][{{ $index }}][harga_satuan]" id="harga_satuan"
                          class="form-control @error('sub_akun.0.kegiatan.' . $index . '.harga_satuan') is-invalid @enderror"
                          placeholder="Harga" value="{{ old('sub_akun.0.kegiatan.' . $index . '.harga_satuan') }}">
                        @error('sub_akun.0.kegiatan.' . $index . '.harga_satuan')
                          <x-input-validation>{{ $message }}</x-input-validation>
                        @enderror
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <button type="button" class="btn btn-sm btn-success" onclick="addKegiatan()">Tambah Kegiatan</button>
            </div>
          </div>

          <div class="mt-2">
            <a href="{{ route('akun.index') }}">
              <button type="button" class="btn btn-secondary">Kembali</button>
            </a>
            <button type="submit" class="btn btn-primary">Simpan data</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
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
        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.kegiatan-item').remove()">Hapus</button>
      </div>
      <div class="row g-2">
        <div class="col-md-2">
          <input type="text" name="sub_akun[0][kegiatan][${kegiatanIndex}][kode_akun_kegiatan]"
                 class="form-control"
                 placeholder="Contoh. 521224">
        </div>
        <div class="col-md-6">
          <input type="text" name="sub_akun[0][kegiatan][${kegiatanIndex}][nama_kegiatan]"
                 class="form-control"
                 placeholder="Nama kegiatan">
        </div>
        <div class="col-md-1">
          <input type="number" name="sub_akun[0][kegiatan][${kegiatanIndex}][jumlah_sampel]"
                 class="form-control"
                 placeholder="Jumlah">
        </div>
        <div class="col-md-1">
          <input type="text" name="sub_akun[0][kegiatan][${kegiatanIndex}][satuan]"
                 class="form-control"
                 placeholder="Satuan">
        </div>
        <div class="col-md-2">
          <input type="text" name="sub_akun[0][kegiatan][${kegiatanIndex}][harga_satuan]"
                 class="form-control harga-satuan"
                 placeholder="Harga">
        </div>
      </div>
    `;

      container.appendChild(div);

      const firstKodeInput = container.querySelector('input[name*="[kode_akun_kegiatan]"]');
      const newKodeInput = div.querySelector('input[name*="[kode_akun_kegiatan]"]');
      if (firstKodeInput && firstKodeInput.value) {
        newKodeInput.value = firstKodeInput.value;
      }

      const newHargaInput = div.querySelector('.harga-satuan');
      if (newHargaInput) {
        applyRupiahFormat(newHargaInput);
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      const kodeAkunInput = document.getElementById('kode_akun');
      const kodeSubAkunInput = document.querySelector('input[name="sub_akun[0][kode_sub_akun]"]');

      if (kodeAkunInput && kodeSubAkunInput) {
        kodeAkunInput.addEventListener('input', function() {
          kodeSubAkunInput.value = this.value;
        });
      }

      const kegiatanContainer = document.getElementById('kegiatan-container');

      function syncKodeAkunKegiatan(value, except = null) {
        const allInputs = kegiatanContainer.querySelectorAll('input[name*="[kode_akun_kegiatan]"]');
        allInputs.forEach(input => {
          if (input !== except) {
            input.value = value;
          }
        });
      }

      kegiatanContainer.addEventListener('input', function(e) {
        if (e.target.name.includes('[kode_akun_kegiatan]')) {
          syncKodeAkunKegiatan(e.target.value, e.target);
        }
      });

      document.querySelectorAll('.harga-satuan, #pagu_anggaran').forEach(function(input) {
        applyRupiahFormat(input);
      });
    });


    function applyRupiahFormat(input) {
      input.addEventListener('input', function() {
        let val = this.value.replace(/[^0-9]/g, '');
        this.value = val ? formatRupiah(val, 'Rp ') : '';
      });

      if (input.value) {
        let val = input.value.replace(/[^0-9]/g, '');
        input.value = formatRupiah(val, 'Rp ');
      }
    }

    function formatRupiah(angka, prefix = 'Rp ') {
      let number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix + rupiah;
    }

    function activateRupiahFormatting(selector) {
      document.querySelectorAll(selector).forEach(function(input) {
        if (input.value) {
          let cleanValue = input.value.replace(/[^0-9]/g, '');
          input.value = formatRupiah(cleanValue);
        }

        input.addEventListener('input', function() {
          let value = this.value.replace(/[^0-9]/g, '');
          this.value = value ? formatRupiah(value) : '';
        });
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      activateRupiahFormatting('#pagu_anggaran, #harga_satuan');

      const container = document.getElementById('kegiatan-container');

      container.addEventListener('DOMNodeInserted', function(e) {
        if (e.target.matches('.kegiatan-item') || e.target.querySelector('#harga_satuan')) {
          activateRupiahFormatting('#harga_satuan');
        }
      });
    });
  </script>
@endsection
