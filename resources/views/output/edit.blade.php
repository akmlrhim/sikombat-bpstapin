@extends('layouts.template')

@section('content')
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-body">
        <form method="POST" action="{{ route('kegiatan.output.update', [$kegiatan->uuid, $output->uuid]) }}">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-12">

              <h4 class="text-primary font-weight-bold">Data Output</h4>

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="kode_output">Kode Output</label>
                  <input type="text" class="form-control @error('kode_output') is-invalid @enderror" name="kode_output"
                    id="kode_output" placeholder="Contoh. 2901.BMA.001"
                    value="{{ old('kode_output', $output->kode_output) }}" />
                  @error('kode_output')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="nama_output">Nama Output</label>
                  <input type="text" class="form-control @error('nama_output') is-invalid @enderror" name="nama_output"
                    id="nama_output" value="{{ old('nama_output', $output->nama_output) }}" />
                  @error('nama_output')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>

                <div class="form-group col-md-6">
                  <label for="deskripsi">Deskripsi</label>
                  <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi"
                    id="deskripsi" value="{{ old('deskripsi', $output->deskripsi) }}" />
                  @error('deskripsi')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>
              </div>

              <hr />
              <h4 class="text-primary font-weight-bold mt-2">Data Komponen</h4>

              <div class="table-responsive m-0" style="overflow-x: auto">
                <table class="table table-sm table-bordered" id="komponen-table" style="min-width: 800px">
                  <thead>
                    <tr class=" text-nowrap text-sm">
                      <th style="width: 10%">Kode Komponen</th>
                      <th style="width: 50%">Nama Komponen</th>
                      <th style="width: 3%">Jumlah Sampel</th>
                      <th style="width: 7%">Satuan</th>
                      <th style="width: 10%">Harga Satuan</th>
                      <th style="width: 2%"></th>
                    </tr>
                  </thead>
                  <tbody id="komponen-wrapper">
                    @php
                      $oldKomponen = old('komponen', $output->komponen->toArray());
                    @endphp

                    @foreach ($oldKomponen as $i => $komponen)
                      <tr class="komponen-item">
                        <td>
                          <input type="text" name="komponen[{{ $i }}][kode_komponen]"
                            class="form-control form-control-sm @error('komponen.' . $i . '.kode_komponen') is-invalid @enderror"
                            value="{{ old('komponen.' . $i . '.kode_komponen', $komponen['kode_komponen']) }}">
                          @error('komponen.' . $i . '.kode_komponen')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td>
                          <input type="text" name="komponen[{{ $i }}][nama_komponen]"
                            class="form-control form-control-sm @error('komponen.' . $i . '.nama_komponen') is-invalid @enderror"
                            value="{{ old('komponen.' . $i . '.nama_komponen', $komponen['nama_komponen']) }}">
                          @error('komponen.' . $i . '.nama_komponen')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td>
                          <input type="text" name="komponen[{{ $i }}][jumlah_sampel]" inputmode="numeric"
                            class="form-control form-control-sm @error('komponen.' . $i . '.jumlah_sampel') is-invalid @enderror"
                            value="{{ old('komponen.' . $i . '.jumlah_sampel', $komponen['jumlah_sampel']) }}">
                          @error('komponen.' . $i . '.jumlah_sampel')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td>
                          <input type="text" name="komponen[{{ $i }}][satuan]"
                            class="form-control form-control-sm @error('komponen.' . $i . '.satuan') is-invalid @enderror"
                            value="{{ old('komponen.' . $i . '.satuan', $komponen['satuan']) }}">
                          @error('komponen.' . $i . '.satuan')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td>
                          <input type="text" name="komponen[{{ $i }}][harga_satuan]"
                            class="form-control form-control-sm rupiah-input text-end @error('kegiatan.' . $i . '.harga_satuan') is-invalid @enderror"
                            value="{{ old('komponen.' . $i . '.harga_satuan', number_format($komponen['harga_satuan'], 0, ',', '.')) }}">
                          @error('komponen.' . $i . '.harga_satuan')
                            <x-input-validation>{{ $message }}</x-input-validation>
                          @enderror
                        </td>

                        <td class="text-center align-middle">
                          <button type="button" class="btn btn-danger btn-sm remove-komponen">&times;</button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>

                </table>
              </div>

              <button type="button" id="add-komponen" class="btn btn-success btn-sm mt-2">Tambah Komponen</button>

              <div class="mt-3">
                <a href="{{ route('kegiatan.output', $output->uuid) }}" class="btn btn-secondary">Kembali</a>
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
      $('#id_kegiatan').on('change', function() {
        const kodeAkun = $(this).find('option:selected').data('kode') || '';
        $('#kode_output').val(kodeAkun);
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const tbody = document.getElementById('komponen-wrapper');
      const addBtn = document.getElementById('add-komponen');

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
        newRow.classList.add('komponen-item');
        newRow.innerHTML = `
        <td>
          <input type="text" name="komponen[${index}][kode_komponen]" 
                class="form-control form-control-sm" placeholder="Cth. 521213">
        </td>
        <td>
          <input type="text" name="komponen[${index}][nama_komponen]" 
                class="form-control form-control-sm" placeholder="Cth. Honor Petugas Lapangan">
        </td>
        <td>
          <input type="text" inputmode="numeric" name="komponen[${index}][jumlah_sampel]" 
                class="form-control form-control-sm">
        </td>
        <td>
          <input type="text" name="komponen[${index}][satuan]" 
                class="form-control form-control-sm" placeholder="Cth. OJP">
        </td>
        <td>
          <input type="text" name="komponen[${index}][harga_satuan]" 
                class="form-control form-control-sm rupiah-input text-end">
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm remove-komponen">&times;</button>
        </td>
      `;
        tbody.appendChild(newRow);
        index++;
      });

      tbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-komponen')) {
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
