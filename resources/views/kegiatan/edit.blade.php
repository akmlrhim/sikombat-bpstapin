@extends('layouts.template')

@section('content')
  <div class="col-md-6">
    <div class="card card-primary">
      <div class="card-body">
        <form method="POST" action="{{ route('kegiatan.update', $kegiatan->uuid) }}">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Kode kegiatan</label>
                <input type="text" name="kode_kegiatan" class="form-control @error('kode_kegiatan') is-invalid @enderror"
                  value="{{ old('kode_kegiatan', $kegiatan->kode_kegiatan) }}">
                @error('kode_kegiatan')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label>Nama kegiatan</label>
                <input type="text" name="nama_kegiatan"
                  class="form-control @error('nama_kegiatan') is-invalid @enderror"
                  value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}">
                @error('nama_kegiatan')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label>Pagu Anggaran</label>
                <input type="text" inputmode="numeric" name="pagu_anggaran" id="pagu_anggaran"
                  class="format-rupiah form-control @error('pagu_anggaran') is-invalid @enderror"
                  value="{{ old('pagu_anggaran', $kegiatan->pagu_anggaran) }}">
                @error('pagu_anggaran')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group">
                <label>Sisa Anggaran</label>
                <input type="text" inputmode="numeric" name="sisa_anggaran" id="sisa_anggaran"
                  class="format-rupiah form-control @error('sisa_anggaran') is-invalid @enderror"
                  value="{{ old('sisa_anggaran', $kegiatan->sisa_anggaran) }}">
                @error('sisa_anggaran')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>
            </div>
          </div>

          <div class="mt-3">
            <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      function applyRupiahFormat(input) {
        input.addEventListener('input', function(e) {
          let val = this.value.replace(/[^0-9]/g, '');
          this.value = val ? formatRupiah(val, 'Rp ') : '';
        });

        let initVal = input.value.replace(/[^0-9]/g, '');
        if (initVal) input.value = formatRupiah(initVal, 'Rp ');
      }

      document.querySelectorAll('.format-rupiah').forEach(el => applyRupiahFormat(el));

      function formatRupiah(angka, prefix) {
        let number_string = angka.toString(),
          sisa = number_string.length % 3,
          rupiah = number_string.substr(0, sisa),
          ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
          let separator = sisa ? '.' : '';
          rupiah += separator + ribuan.join('.');
        }

        return prefix + rupiah;
      }
    });
  </script>
@endsection
