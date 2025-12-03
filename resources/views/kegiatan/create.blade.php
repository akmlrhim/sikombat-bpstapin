@extends('layouts.template')

@section('content')
  <div class="col-md-6">
    <div class="card card-primary">

      <div class="card-body">
        <form method="POST" action="{{ route('kegiatan.store') }}">
          @csrf
          <div class="row">
            <div class="col-md-12">

              <div class="form-group">
                <label for="kode_kegiatan">Kode Kegiatan</label>
                <input type="text" class="form-control @error('kode_kegiatan') is-invalid @enderror" id="kode_kegiatan"
                  name="kode_kegiatan" placeholder="Contoh. 2988" value="{{ old('kode_kegiatan') }}">
                @error('kode_kegiatan')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama_kegiatan">Nama Kegiatan</label>
                <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="nama_kegiatan"
                  name="nama_kegiatan" placeholder="Contoh. Penyediaan dan Pengembangan ..."
                  value="{{ old('nama_kegiatan') }}">
                @error('nama_kegiatan')
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

            </div>
          </div>

          <div class="mt-2">
            <a href="{{ route('kegiatan.index') }}">
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
      activateRupiahFormatting('#pagu_anggaran');
    });
  </script>
@endsection
