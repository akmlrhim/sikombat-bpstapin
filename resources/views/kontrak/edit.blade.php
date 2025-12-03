@extends('layouts.template')
@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">

        <div class="card-body">
          <form method="POST" action="{{ route('kontrak.update', $kontrak->uuid) }}">
            @csrf
            @method('PUT')

            <h4 class="text-primary font-weight-bold">Data Kontrak</h4>

            <div class="form-row">
              {{-- BAGIAN DATA KONTRAK UTAMA  --}}
              {{-- mitra  --}}
              <div class="form-group col-md-6">
                <label for="id_mitra">Mitra</label>
                <select class="form-control select2 @error('id_mitra') is-invalid @enderror" id="id_mitra"
                  name="id_mitra" style="width: 100%;">
                  <option value="" disabled {{ old('id_mitra', $kontrak->id_mitra) ? '' : 'selected' }}>
                    -- Silahkan pilih mitra --
                  </option>
                  @foreach ($mitra as $row)
                    <option value="{{ $row->id }}"
                      {{ old('id_mitra', $kontrak->id_mitra) == $row->id ? 'selected' : '' }}>
                      {{ $row->nms }} - {{ $row->nama_lengkap }}
                    </option>
                  @endforeach
                </select>
                @error('id_mitra')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- periode  --}}
              <div class="form-group col-md-6">
                <label for="periode" id="periode">Periode</label>
                <input type="month" name="periode" id="periode"
                  class="form-control @error('periode') is-invalid @enderror"
                  value="{{ old('periode', $kontrak->periode->format('Y-m')) }}" onclick="this.showPicker()" />
                @error('periode')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tanggal --}}
              <div class="form-group col-md-4">
                <label for="tanggal_kontrak" id="tanggal_kontrak">Tanggal Kontrak</label>
                <input type="date" name="tanggal_kontrak" id="tanggal_kontrak"
                  class="form-control @error('tanggal_kontrak') is-invalid @enderror"
                  value="{{ old('tanggal_kontrak', $kontrak->tanggal_kontrak->format('Y-m-d')) }}"
                  onclick="this.showPicker()" />
                @error('tanggal_kontrak')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group col-md-4">
                <label for="tanggal_bast" id="tanggal_bast">Tanggal BAST
                  <small class="text-muted text-sm font-italic">Berita acara serah terima</small>
                </label>
                <input type="date" name="tanggal_bast" id="tanggal_bast"
                  class="form-control @error('tanggal_bast') is-invalid @enderror"
                  value="{{ old('tanggal_bast', $kontrak->tanggal_bast->format('Y-m-d')) }}"
                  onclick="this.showPicker()" />
                @error('tanggal_bast')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group col-md-4">
                <label for="tanggal_surat" id="tanggal_surat">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" id="tanggal_surat"
                  class="form-control @error('tanggal_surat') is-invalid @enderror"
                  value="{{ old('tanggal_surat', $kontrak->tanggal_surat->format('Y-m-d')) }}"
                  onclick="this.showPicker()" />
                @error('tanggal_surat')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group col-md-6">
                <label for="tanggal_mulai" id="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                  class="form-control @error('tanggal_mulai') is-invalid @enderror"
                  value="{{ old('tanggal_mulai', $kontrak->tanggal_mulai->format('Y-m-d')) }}"
                  onclick="this.showPicker()" />
                @error('tanggal_mulai')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group col-md-6">
                <label for="tanggal_berakhir" id="tanggal_berakhir">Tanggal Berakhir</label>
                <input type="date" name="tanggal_berakhir" id="tanggal_berakhir"
                  class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                  value="{{ old('tanggal_berakhir', $kontrak->tanggal_berakhir->format('Y-m-d')) }}"
                  onclick="this.showPicker()" />
                @error('tanggal_berakhir')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              <div class="form-group col-md-12">
                <label for="keterangan" id="keterangan">Keterangan (opsional)</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan keterangan..." class="form-control" cols="30"
                  rows="2">{{ old('keterangan', $kontrak->keterangan) }}</textarea>
              </div>
            </div>

            <hr />

            {{-- BAGIAN DETAIL KEGIATAN KONTRAK  --}}
            <h4 class="text-primary font-weight-bold">Detail Tugas</h4>

            <div id="detail-wrapper">
              @php
                $oldDetail = old('detail', $kontrak->detail->toArray());
              @endphp

              @foreach ($oldDetail as $i => $d)
                <div class="detail-item border p-3 mb-3" data-old-output="{{ $d['id_output'] ?? '' }}"
                  data-old-komponen="{{ $d['id_komponen'] ?? '' }}">

                  <div class="row mb-2">
                    <div class="col-md-6">
                      <select name="detail[{{ $i }}][id_kegiatan]" class="custom-select kegiatan-select">
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach ($kegiatan as $k)
                          <option value="{{ $k->id }}"
                            {{ old("detail.$i.id_kegiatan", $d['id_kegiatan'] ?? '') == $k->id ? 'selected' : '' }}>
                            {{ $k->kode_kegiatan }} - {{ $k->nama_kegiatan }}
                          </option>
                        @endforeach
                      </select>
                      @error("detail.$i.id_kegiatan")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <select name="detail[{{ $i }}][id_output]" class="custom-select output-select">
                        <option value="">-- Pilih Komponen --</option>
                      </select>
                      @error("detail.$i.id_output")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>
                  </div>

                  <div class="row">
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
                        class="form-control" placeholder="Masukkan Jumlah Target Dokumen"
                        value="{{ old("detail.$i.jumlah_target_dokumen", $d['jumlah_target_dokumen'] ?? '') }}">
                      @error("detail.$i.jumlah_target_dokumen")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <input type="number" name="detail[{{ $i }}][jumlah_dokumen]" class="form-control"
                        placeholder="Masukkan jumlah sampel/dokumen"
                        value="{{ old("detail.$i.jumlah_dokumen", $d['jumlah_dokumen'] ?? '') }}">
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

            {{-- template for new data  --}}
            <div id="detail-template" class="d-none">
              <div class="detail-item border p-3 mb-3" data-old-output="" data-old-komponen="">
                <div class="row mb-2">
                  <div class="col-md-6">
                    <select name="__NAME__" class="custom-select kegiatan-select">
                      <option value="">-- Pilih Kegiatan --</option>
                      @foreach ($kegiatan as $k)
                        <option value="{{ $k->id }}">{{ $k->kode_kegiatan }} - {{ $k->nama_kegiatan }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-6">
                    <select name="__NAME__" class="custom-select output-select">
                      <option value="">-- Pilih Output --</option>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <select name="__NAME__" class="custom-select komponen-select">
                      <option value="">-- Pilih Komponen --</option>
                    </select>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-md-6">
                    <input type="number" name="__NAME__" class="form-control"
                      placeholder="Masukkan Jumlah Target Dokumen" value="">
                  </div>

                  <div class="col-md-6">
                    <input type="number" name="__NAME__" class="form-control"
                      placeholder="Masukkan jumlah sampel/dokumen" value="">
                  </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2 remove-item">
                  Hapus
                </button>
              </div>
            </div>

            <button type="button" id="addDetail" class="btn btn-primary btn-sm mt-2">+ Tambah</button>

            <div class="mt-2">
              <a href="{{ route('kontrak.index') }}" class="btn btn-secondary">Kembali</a>
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
      $('.select2').select2();
    });

    $(document).ready(function() {

      let index = $('#detail-wrapper .detail-item').length;

      function setNames($item, idx) {
        $item.find('select.kegiatan-select').attr('name', `detail[${idx}][id_kegiatan]`);
        $item.find('select.output-select').attr('name', `detail[${idx}][id_output]`);
        $item.find('select.komponen-select').attr('name', `detail[${idx}][id_kegiatan]`);
        $item.find('input[name="__NAME__"]').each(function(i) {

        });

        $item.find('input').filter(function() {
            return $(this).attr('placeholder') && $(this).attr('placeholder').includes('Target');
          })
          .attr('name', `detail[${idx}][jumlah_target_dokumen]`);
        $item.find('input').filter(function() {
            return $(this).attr('placeholder') && $(this).attr('placeholder').includes('jumlah sampel');
          })
          .attr('name', `detail[${idx}][jumlah_dokumen]`);
      }

      $('#addDetail').click(function() {
        let tpl = $('#detail-template .detail-item').first().clone();
        setNames(tpl, index);

        tpl.attr('data-old-output', '');
        tpl.attr('data-old-komponen', '');

        $('#detail-wrapper').append(tpl);

        initSelect2For(tpl);

        index++;
      });

      $(document).on('click', '.remove-item', function() {
        if ($('#detail-wrapper .detail-item').length === 1) {
          $(this).closest('.detail-item').find('select, input').val('');
          return;
        }
        $(this).closest('.detail-item').remove();

        $('#detail-wrapper .detail-item').each(function(i) {
          setNames($(this), i);
        });
        index = $('#detail-wrapper .detail-item').length;
      });

      $(document).on('change', '.kegiatan-select', function() {
        let parent = $(this).closest('.detail-item');
        let kegiatanID = $(this).val();
        let outputSelect = parent.find('.output-select');
        let komponenSelect = parent.find('.komponen-select');

        outputSelect.html('<option>Loading...</option>').prop('disabled', true);
        komponenSelect.html('<option>-- Pilih Komponen --</option>').prop('disabled', true);

        if (kegiatanID) {
          $.get('/ajax/output/' + kegiatanID, function(res) {
            outputSelect.html('<option value="">-- Pilih Output --</option>');
            res.forEach(function(r) {
              outputSelect.append(
                `<option value="${r.id}">${r.kode_output} - ${r.nama_output}</option>`);
            });
            outputSelect.prop('disabled', false);

            let oldOutput = parent.attr('data-old-output');
            if (oldOutput) {
              outputSelect.val(oldOutput).trigger('change');
              parent.attr('data-old-output', '');
            }
          }).fail(function() {
            outputSelect.html('<option value="">-- Pilih Output --</option>').prop('disabled', false);
          });
        } else {
          outputSelect.html('<option value="">-- Pilih Output --</option>').prop('disabled', false);
        }
      });

      $(document).on('change', '.output-select', function() {
        let parent = $(this).closest('.detail-item');
        let outputID = $(this).val();
        let komponenSelect = parent.find('.komponen-select');

        komponenSelect.html('<option>Loading...</option>').prop('disabled', true);

        if (outputID) {
          $.get('/ajax/komponen/' + outputID, function(res) {
            komponenSelect.html('<option value="">-- Pilih komponen --</option>');
            res.forEach(function(r) {
              komponenSelect.append(
                `<option value="${r.id}">${r.kode_komponen} - ${r.nama_komponen}</option>`);
            });
            komponenSelect.prop('disabled', false);

            let oldKomponen = parent.attr('data-old-komponen');
            if (oldKomponen) {
              komponenSelect.val(oldKomponen);
              parent.attr('data-old-komponen', '');
            }
          }).fail(function() {
            komponenSelect.html('<option value="">-- Pilih Komponen --</option>').prop('disabled', false);
          });
        } else {
          komponenSelect.html('<option value="">-- Pilih Komponen --</option>').prop('disabled', false);
        }
      });

      $('#detail-wrapper .detail-item').each(function() {
        let oldKegiatan = $(this).find('.kegiatan-select').val();
        if (oldKegiatan) {
          $(this).find('.kegiatan-select').trigger('change');
        }
      });

    });
  </script>
@endsection
