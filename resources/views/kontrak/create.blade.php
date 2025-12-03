@extends('layouts.template')
@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">

        <div class="card-body">
          <form method="POST" action="{{ route('kontrak.store') }}">
            @csrf

            <h4 class="text-primary font-weight-bold">Data Kontrak</h4>

            <div class="form-row">
              {{-- BAGIAN DATA KONTRAK UTAMA  --}}

              {{-- mitra  --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="id_mitra">Mitra</label>
                <select class="form-control select2 @error('id_mitra') is-invalid @enderror" id="id_mitra"
                  name="id_mitra">
                  <option value="" disabled {{ old('id_mitra') ? '' : 'selected' }}>-- Silahkan pilih mitra --
                  </option>
                  @foreach ($mitra as $row)
                    <option value="{{ $row->id }}" {{ old('id_mitra') == $row->id ? 'selected' : '' }}>
                      {{ $row->nms }} - {{ $row->nama_lengkap }}
                    </option>
                  @endforeach
                </select>
                @error('id_mitra')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- sebagai --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="sebagai">Bertugas sebagai apa</label>
                <select class="form-control select2 @error('sebagai') is-invalid @enderror" id="sebagai" name="sebagai">
                  <option value="" disabled {{ old('sebagai') ? '' : 'selected' }}>-- Pilih bertugas sebagai apa --
                  </option>
                  <option value="Pengumpulan Data" {{ old('sebagai') == 'Pengumpulan Data' ? 'selected' : '' }}>
                    Pengumpulan Data</option>
                  <option value="Pengolahan Data" {{ old('sebagai') == 'Pengolahan Data' ? 'selected' : '' }}>Pengolahan
                    Data</option>
                  <option value="Pemeriksa Data" {{ old('sebagai') == 'Pemeriksa Data' ? 'selected' : '' }}>Pemeriksa
                    Data</option>
                </select>
                @error('sebagai')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- periode --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="periode">Periode</label>
                <input type="month" name="periode" id="periode"
                  class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode') }}"
                  onclick="this.showPicker()" />
                @error('periode')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tanggal kontrak --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="tanggal_kontrak">Tanggal Kontrak</label>
                <input type="date" name="tanggal_kontrak" id="tanggal_kontrak"
                  class="form-control @error('tanggal_kontrak') is-invalid @enderror"
                  value="{{ old('tanggal_kontrak') }}" onclick="this.showPicker()" />
                @error('tanggal_kontrak')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tanggal BAST --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="tanggal_bast">Tanggal BAST</label>
                <input type="date" name="tanggal_bast" id="tanggal_bast"
                  class="form-control @error('tanggal_bast') is-invalid @enderror" value="{{ old('tanggal_bast') }}"
                  onclick="this.showPicker()" />
                @error('tanggal_bast')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tanggal surat --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="tanggal_surat">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" id="tanggal_surat"
                  class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat') }}"
                  onclick="this.showPicker()" />
                @error('tanggal_surat')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tanggal mulai --}}
              <div class="form-group col-md-6">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                  class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}"
                  onclick="this.showPicker()" />
                @error('tanggal_mulai')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tanggal berakhir --}}
              <div class="form-group col-md-6">
                <label for="tanggal_berakhir">Tanggal Berakhir</label>
                <input type="date" name="tanggal_berakhir" id="tanggal_berakhir"
                  class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                  value="{{ old('tanggal_berakhir') }}" onclick="this.showPicker()" />
                @error('tanggal_berakhir')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- keterangan --}}
              <div class="form-group col-md-12">
                <label for="keterangan">Keterangan (opsional)</label>
                <textarea name="keterangan" id="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
              </div>
            </div>

            <hr>

            {{-- DETAIL KEGIATAN --}}
            <h4 class="text-primary font-weight-bold">Detail Kegiatan</h4>

            <div id="detail-wrapper">

              @php
                $oldDetail = old('detail', [
                    [
                        'id_kegiatan' => '',
                        'id_output' => '',
                        'id_komponen' => '',
                        'jumlah_target_dokumen' => '',
                        'jumlah_dokumen' => '',
                    ],
                ]);
              @endphp

              @foreach ($oldDetail as $i => $d)
                <div class="detail-item border p-2 rounded mb-3" data-old-output="{{ $d['id_output'] ?? '' }}"
                  data-old-komponen="{{ $d['id_komponen'] ?? '' }}">

                  {{-- kegiatan --}}
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <select name="detail[{{ $i }}][id_kegiatan]" class="custom-select kegiatan-select">
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach ($kegiatan as $a)
                          <option value="{{ $a->id }}"
                            {{ old("detail.$i.id_kegiatan", $d['id_kegiatan']) == $a->id ? 'selected' : '' }}>
                            {{ $a->kode_kegiatan }} - {{ $a->nama_kegiatan }}
                          </option>
                        @endforeach
                      </select>
                      @error("detail.$i.id_kegiatan")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>

                    {{-- output --}}
                    <div class="col-md-6">
                      <select name="detail[{{ $i }}][id_output]" class="custom-select output-select">
                        <option value="">-- Pilih Output --</option>
                      </select>
                      @error("detail.$i.id_output")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>
                  </div>

                  {{-- komponen --}}
                  <div class="row mb-2">
                    <div class="col-md-12">
                      <select name="detail[{{ $i }}][id_komponen]" class="custom-select komponen-select">
                        <option value="">-- Pilih Komponen --</option>
                      </select>
                      @error("detail.$i.id_komponen")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-md-6">
                      <input type="number" name="detail[{{ $i }}][jumlah_target_dokumen]"
                        value="{{ old("detail.$i.jumlah_target_dokumen", $d['jumlah_target_dokumen']) }}"
                        class="form-control" placeholder="Jumlah target dokumen">
                      @error("detail.$i.jumlah_target_dokumen")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <input type="number" name="detail[{{ $i }}][jumlah_dokumen]"
                        value="{{ old("detail.$i.jumlah_dokumen", $d['jumlah_dokumen']) }}" class="form-control"
                        placeholder="Jumlah dokumen">
                      @error("detail.$i.jumlah_dokumen")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>
                  </div>

                  <button type="button" class="btn btn-danger btn-sm mt-2 remove-item {{ $i == 0 ? 'd-none' : '' }}">
                    Hapus
                  </button>

                </div>
              @endforeach

            </div>

            <button type="button" id="addDetail" class="btn btn-primary btn-sm">+ Tambah</button>

            <div class="mt-3 d-flex">
              <a href="{{ route('kontrak.index') }}" class="btn btn-secondary mr-2">Kembali</a>
              <button type="submit" class="btn btn-primary">Simpan data</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(function() {
      $('.select2').select2()
    })

    $(document).ready(function() {

      let index = {{ count(old('detail', [[]])) }};

      // Tambah baris detail
      $('#addDetail').click(function() {
        let clone = $('.detail-item:first').clone();

        clone.find('select, input').each(function() {
          let name = $(this).attr('name');
          name = name.replace(/\[\d+\]/, '[' + index + ']');
          $(this).attr('name', name).val('');
        });

        clone.find('.remove-item').removeClass('d-none');
        clone.find('.output-select').html('<option>-- Pilih Output --</option>').prop('disabled', true);
        clone.find('.komponen-select').html('<option>-- Pilih Komponen --</option>').prop('disabled', true);

        $('#detail-wrapper').append(clone);
        index++;
      });

      $(document).on('click', '.remove-item', function() {
        $(this).closest('.detail-item').remove();
      });

      // Kegiatan → Output
      $(document).on('change', '.kegiatan-select', function() {
        let parent = $(this).closest('.detail-item');
        let kegiatanID = $(this).val();
        let outputSelect = parent.find('.output-select');
        let komponenSelect = parent.find('.komponen-select');

        outputSelect.html('<option>Loading...</option>').prop('disabled', true);
        komponenSelect.html('<option>-- Pilih Komponen --</option>').prop('disabled', true);

        if (kegiatanID) {
          $.get('/ajax/output/' + kegiatanID, function(res) {
            outputSelect.html('<option>-- Pilih Output --</option>');
            res.forEach(function(r) {
              outputSelect.append(`<option value="${r.id}">${r.kode_output} - ${r.nama_output}</option>`);
            });
            outputSelect.prop('disabled', false);

            let oldOutput = parent.attr('data-old-output');
            if (oldOutput) outputSelect.val(oldOutput).trigger('change');
          });
        }
      });

      // Output → Komponen
      $(document).on('change', '.output-select', function() {
        let parent = $(this).closest('.detail-item');
        let outputID = $(this).val();
        let komSelect = parent.find('.komponen-select');

        komSelect.html('<option>Loading...</option>').prop('disabled', true);

        if (outputID) {
          $.get('/ajax/komponen/' + outputID, function(res) {
            komSelect.html('<option>-- Pilih Komponen --</option>');
            res.forEach(function(r) {
              komSelect.append(
                `<option value="${r.id}">${r.kode_komponen} - ${r.nama_komponen}</option>`);
            });
            komSelect.prop('disabled', false);

            let oldKom = parent.attr('data-old-komponen');
            if (oldKom) komSelect.val(oldKom);
          });
        }
      });

      // Trigger old value
      $('.detail-item').each(function() {
        let oldKeg = $(this).find('.kegiatan-select').val();
        if (oldKeg) $(this).find('.kegiatan-select').trigger('change');
      });

    });
  </script>
@endsection
