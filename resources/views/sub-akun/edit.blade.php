@extends('layouts.template')

@section('content')
  <x-alert />

  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-body">
        <form method="POST" action="{{ route('akun.sub-akun.update', [$akun->uuid, $subAkun->uuid]) }}">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-12">

              <h4 class="text-primary font-weight-bold">Edit Sub Akun</h4>

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="kode_sub_akun">Kode Sub Akun</label>
                  <input type="text" class="form-control @error('kode_sub_akun') is-invalid @enderror"
                    name="kode_sub_akun" id="kode_sub_akun" placeholder="Contoh. 2901.BMA.001"
                    value="{{ old('kode_sub_akun', $subAkun->kode_sub_akun) }}" />
                  @error('kode_sub_akun')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="nama_sub_akun">Nama Sub Akun</label>
                  <input type="text" class="form-control @error('nama_sub_akun') is-invalid @enderror"
                    name="nama_sub_akun" id="nama_sub_akun" value="{{ old('nama_sub_akun', $subAkun->nama_sub_akun) }}" />
                  @error('nama_sub_akun')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>

                <div class="form-group col-md-6">
                  <label for="nama_kegiatan_sub_akun">Nama Kegiatan Sub Akun</label>
                  <input type="text" class="form-control @error('nama_kegiatan_sub_akun') is-invalid @enderror"
                    name="nama_kegiatan_sub_akun" id="nama_kegiatan_sub_akun"
                    value="{{ old('nama_kegiatan_sub_akun', $subAkun->nama_kegiatan_sub_akun) }}" />
                  @error('nama_kegiatan_sub_akun')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>
              </div>

              <hr />
              <h4 class="text-primary font-weight-bold mt-2">Data Kegiatan</h4>

              <div class="table-responsive m-0" style="overflow-x: auto">
                <table class="table table-sm table-bordered" id="kegiatan-table" style="min-width: 800px">
                  <thead>
                    <tr class=" text-nowrap text-sm">
                      <th style="width: 10%">Kd. Akun Kegiatan</th>
                      <th style="width: 50%">Nama Kegiatan</th>
                      <th style="width: 3%">Jlh.Sampel</th>
                      <th style="width: 7%">Satuan</th>
                      <th style="width: 10%">Harga Satuan</th>
                      <th style="width: 2%"></th>
                    </tr>
                  </thead>
                  <tbody id="kegiatan-wrapper">
                    @php
                      $oldKegiatan = old('kegiatan', $subAkun->kegiatan->toArray());
                    @endphp

                    @foreach ($oldKegiatan as $i => $kegiatan)
                      <tr class="kegiatan-item">
                        <td>
                          <input type="text" name="kegiatan[{{ $i }}][kode_akun_kegiatan]"
                            class="form-control form-control-sm @error('kegiatan.' . $i . '.kode_akun_kegiatan') is-invalid @enderror"
                            value="{{ old('kegiatan.' . $i . '.kode_akun_kegiatan', $kegiatan['kode_akun_kegiatan']) }}">
                          @error('kegiatan.' . $i . '.kode_akun_kegiatan')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td>
                          <input type="text" name="kegiatan[{{ $i }}][nama_kegiatan]"
                            class="form-control form-control-sm @error('kegiatan.' . $i . '.nama_kegiatan') is-invalid @enderror"
                            value="{{ old('kegiatan.' . $i . '.nama_kegiatan', $kegiatan['nama_kegiatan']) }}">
                          @error('kegiatan.' . $i . '.nama_kegiatan')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td>
                          <input type="text" name="kegiatan[{{ $i }}][jumlah_sampel]" inputmode="numeric"
                            class="form-control form-control-sm @error('kegiatan.' . $i . '.jumlah_sampel') is-invalid @enderror"
                            value="{{ old('kegiatan.' . $i . '.jumlah_sampel', $kegiatan['jumlah_sampel']) }}">
                          @error('kegiatan.' . $i . '.jumlah_sampel')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td>
                          <input type="text" name="kegiatan[{{ $i }}][satuan]"
                            class="form-control form-control-sm @error('kegiatan.' . $i . '.satuan') is-invalid @enderror"
                            value="{{ old('kegiatan.' . $i . '.satuan', $kegiatan['satuan']) }}">
                          @error('kegiatan.' . $i . '.satuan')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td>
                          <input type="text" name="kegiatan[{{ $i }}][harga_satuan]"
                            class="form-control form-control-sm rupiah-input text-end @error('kegiatan.' . $i . '.harga_satuan') is-invalid @enderror"
                            value="{{ old('kegiatan.' . $i . '.harga_satuan', number_format($kegiatan['harga_satuan'], 0, ',', '.')) }}">
                          @error('kegiatan.' . $i . '.harga_satuan')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td class="text-center align-middle">
                          <button type="button" class="btn btn-danger btn-sm remove-kegiatan">&times;</button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>

                </table>
              </div>

              <button type="button" id="add-kegiatan" class="btn btn-success btn-sm mt-2">Tambah Kegiatan</button>

              <div class="mt-3">
                <a href="{{ route('akun.sub-akun', $akun->uuid) }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Update Data</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {
      $('#id_akun').on('change', function() {
        const kodeAkun = $(this).find('option:selected').data('kode') || '';
        $('#kode_sub_akun').val(kodeAkun);
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const tbody = document.getElementById('kegiatan-wrapper');
      const addBtn = document.getElementById('add-kegiatan');

      let index = tbody.querySelectorAll('tr').length;

      function formatRupiah(angka) {
        const numberString = angka.replace(/[^\d]/g, '');
        const sisa = numberString.length % 3;
        let rupiah = numberString.substr(0, sisa);
        const ribuan = numberString.substr(sisa).match(/\d{3}/g);
        if (ribuan) {
          const separator = sisa ? '.' : '';
          rupiah += separator + ribuan.join('.');
        }
        return rupiah ? 'Rp ' + rupiah : '';
      }

      addBtn.addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.classList.add('kegiatan-item');
        newRow.innerHTML = `
        <td>
          <input type="text" name="kegiatan[${index}][kode_akun_kegiatan]" 
                class="form-control form-control-sm" placeholder="Cth. 521213">
        </td>
        <td>
          <input type="text" name="kegiatan[${index}][nama_kegiatan]" 
                class="form-control form-control-sm" placeholder="Cth. Honor Petugas Lapangan">
        </td>
        <td>
          <input type="text" inputmode="numeric" name="kegiatan[${index}][jumlah_sampel]" 
                class="form-control form-control-sm">
        </td>
        <td>
          <input type="text" name="kegiatan[${index}][satuan]" 
                class="form-control form-control-sm" placeholder="Cth. OJP">
        </td>
        <td>
          <input type="text" name="kegiatan[${index}][harga_satuan]" 
                class="form-control form-control-sm rupiah-input text-end">
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm remove-kegiatan">&times;</button>
        </td>
      `;
        tbody.appendChild(newRow);
        index++;
      });

      tbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-kegiatan')) {
          e.target.closest('tr').remove();
          reindexRows();
        }
      });

      function reindexRows() {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((tr, i) => {
          tr.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name');
            if (name) input.setAttribute('name', name.replace(/\[\d+\]/, `[${i}]`));
          });
        });
        index = rows.length;
      }

      document.addEventListener('input', function(e) {
        if (e.target.classList.contains('rupiah-input')) {
          const input = e.target;
          const cursorPos = input.selectionStart;
          const oldLength = input.value.length;
          input.value = formatRupiah(input.value);
          const newLength = input.value.length;
          input.selectionEnd = cursorPos + (newLength - oldLength);
        }
      });

      tbody.querySelectorAll('.rupiah-input').forEach(input => {
        if (input.value) {
          input.value = formatRupiah(input.value);
        }
      });
    });
  </script>
@endsection
