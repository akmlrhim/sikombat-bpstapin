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
            <h4 class="text-primary font-weight-bold">Detail Kegiatan</h4>

            <div id="detail-wrapper">
              {{-- render existing detail (old or from model) --}}
              @php
                $oldDetail = old('detail', $kontrak->detail->toArray());
              @endphp

              @foreach ($oldDetail as $i => $d)
                <div class="detail-item border p-3 mb-3" data-old-sub="{{ $d['id_sub_akun'] ?? '' }}"
                  data-old-keg="{{ $d['id_kegiatan'] ?? '' }}">

                  <div class="row mb-2">
                    <div class="col-md-6">
                      <select name="detail[{{ $i }}][id_akun]" class="custom-select akun-select">
                        <option value="">-- Pilih Akun --</option>
                        @foreach ($akun as $a)
                          <option value="{{ $a->id }}"
                            {{ old("detail.$i.id_akun", $d['id_akun'] ?? '') == $a->id ? 'selected' : '' }}>
                            {{ $a->kode_akun }} - {{ $a->nama_akun }}
                          </option>
                        @endforeach
                      </select>
                      @error("detail.$i.id_akun")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <select name="detail[{{ $i }}][id_sub_akun]" class="custom-select sub-akun-select">
                        <option value="">-- Pilih Sub Akun --</option>
                      </select>
                      @error("detail.$i.id_sub_akun")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <select name="detail[{{ $i }}][id_kegiatan]" class="custom-select kegiatan-select">
                        <option value="">-- Pilih Kegiatan --</option>
                      </select>
                      @error("detail.$i.id_kegiatan")
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
              <div class="detail-item border p-3 mb-3" data-old-sub="" data-old-keg="">
                <div class="row mb-2">
                  <div class="col-md-6">
                    <select name="__NAME__" class="custom-select akun-select">
                      <option value="">-- Pilih Akun --</option>
                      @foreach ($akun as $a)
                        <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-6">
                    <select name="__NAME__" class="custom-select sub-akun-select">
                      <option value="">-- Pilih Sub Akun --</option>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <select name="__NAME__" class="custom-select kegiatan-select">
                      <option value="">-- Pilih Kegiatan --</option>
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

            <button type="button" id="addDetail" class="btn btn-primary btn-sm mt-2">+ Tambah Kegiatan</button>

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
        $item.find('select.akun-select').attr('name', `detail[${idx}][id_akun]`);
        $item.find('select.sub-akun-select').attr('name', `detail[${idx}][id_sub_akun]`);
        $item.find('select.kegiatan-select').attr('name', `detail[${idx}][id_kegiatan]`);
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

        tpl.attr('data-old-sub', '');
        tpl.attr('data-old-keg', '');

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

      $(document).on('change', '.akun-select', function() {
        let parent = $(this).closest('.detail-item');
        let akunID = $(this).val();
        let subSelect = parent.find('.sub-akun-select');
        let kegSelect = parent.find('.kegiatan-select');

        subSelect.html('<option>Loading...</option>').prop('disabled', true);
        kegSelect.html('<option>-- Pilih Kegiatan --</option>').prop('disabled', true);

        if (akunID) {
          $.get('/ajax/sub-akun/' + akunID, function(res) {
            subSelect.html('<option value="">-- Pilih Sub Akun --</option>');
            res.forEach(function(r) {
              subSelect.append(
                `<option value="${r.id}">${r.kode_sub_akun} - ${r.nama_sub_akun}</option>`);
            });
            subSelect.prop('disabled', false);

            let oldSub = parent.attr('data-old-sub');
            if (oldSub) {
              subSelect.val(oldSub).trigger('change');
              parent.attr('data-old-sub', '');
            }
          }).fail(function() {
            subSelect.html('<option value="">-- Pilih Sub Akun --</option>').prop('disabled', false);
          });
        } else {
          subSelect.html('<option value="">-- Pilih Sub Akun --</option>').prop('disabled', false);
        }
      });

      $(document).on('change', '.sub-akun-select', function() {
        let parent = $(this).closest('.detail-item');
        let subID = $(this).val();
        let kegSelect = parent.find('.kegiatan-select');

        kegSelect.html('<option>Loading...</option>').prop('disabled', true);

        if (subID) {
          $.get('/ajax/kegiatan/' + subID, function(res) {
            kegSelect.html('<option value="">-- Pilih Kegiatan --</option>');
            res.forEach(function(r) {
              kegSelect.append(
                `<option value="${r.id}">${r.kode_akun_kegiatan} - ${r.nama_kegiatan}</option>`);
            });
            kegSelect.prop('disabled', false);

            let oldKeg = parent.attr('data-old-keg');
            if (oldKeg) {
              kegSelect.val(oldKeg);
              parent.attr('data-old-keg', '');
            }
          }).fail(function() {
            kegSelect.html('<option value="">-- Pilih Kegiatan --</option>').prop('disabled', false);
          });
        } else {
          kegSelect.html('<option value="">-- Pilih Kegiatan --</option>').prop('disabled', false);
        }
      });

      $('#detail-wrapper .detail-item').each(function() {
        let oldAkun = $(this).find('.akun-select').val();
        if (oldAkun) {
          $(this).find('.akun-select').trigger('change');
        }
      });

    });
  </script>
@endsection
