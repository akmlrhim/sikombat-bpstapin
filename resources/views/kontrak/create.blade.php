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
                  name="id_mitra" style="width: 100%;">
                  <option value="" disabled {{ old('id_mitra') ? '' : 'selected' }}>
                    -- Silahkan pilih mitra --
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

              {{-- sebagai apa  --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="sebagai">Bertugas sebagai apa</label>
                <select class="form-control select2 @error('sebagai') is-invalid @enderror" id="sebagai" name="sebagai"
                  style="width: 100%;">
                  <option value="" disabled {{ old('sebagai') ? '' : 'selected' }}>
                    -- Pilih bertugas sebagai apa --
                  </option>
                  <option value="Pengumpulan Data" {{ old('sebagai') == 'Pengumpulan Data' ? 'selected' : '' }}>
                    Pengumpulan Data
                  </option>

                  <option value="Pengolahan Data">Pengolahan Data</option>
                  <option value="Pemeriksa Data">Pemeriksa Data</option>
                </select>
                @error('sebagai')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- periode  --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="periode" id="periode">Periode</label>
                <input type="month" name="periode" id="periode"
                  class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode') }}"
                  onclick="this.showPicker()" />
                @error('periode')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tgl kontrak --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="tanggal_kontrak" id="tanggal_kontrak">Tanggal Kontrak</label>
                <input type="date" name="tanggal_kontrak" id="tanggal_kontrak"
                  class="form-control @error('tanggal_kontrak') is-invalid @enderror"
                  value="{{ old('tanggal_kontrak') }}" onclick="this.showPicker()" />
                @error('tanggal_kontrak')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tgl bast  --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="tanggal_bast" id="tanggal_bast">Tanggal BAST
                  <small class="text-muted text-sm font-italic">Berita acara serah terima</small>
                </label>
                <input type="date" name="tanggal_bast" id="tanggal_bast"
                  class="form-control @error('tanggal_bast') is-invalid @enderror" value="{{ old('tanggal_bast') }}"
                  onclick="this.showPicker()" />
                @error('tanggal_bast')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tgl surat  --}}
              <div class="form-group col-md-4">
                <label class="mb-0" for="tanggal_surat" id="tanggal_surat">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" id="tanggal_surat"
                  class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat') }}"
                  onclick="this.showPicker()" />
                @error('tanggal_surat')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tgl mulai  --}}
              <div class="form-group col-md-6">
                <label class="mb-0" for="tanggal_mulai" id="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                  class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}"
                  onclick="this.showPicker()" />
                @error('tanggal_mulai')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- tgl berakhir  --}}
              <div class="form-group col-md-6">
                <label class="mb-0" for="tanggal_berakhir" id="tanggal_berakhir">Tanggal Berakhir</label>
                <input type="date" name="tanggal_berakhir" id="tanggal_berakhir"
                  class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                  value="{{ old('tanggal_berakhir') }}" onclick="this.showPicker()" />
                @error('tanggal_berakhir')
                  <x-input-validation>{{ $message }}</x-input-validation>
                @enderror
              </div>

              {{-- keterangan  --}}
              <div class="form-group col-md-12">
                <label class="mb-0" for="keterangan" id="keterangan">Keterangan (opsional)</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan keterangan..." class="form-control" cols="30"
                  rows="2">{{ old('keterangan') }}</textarea>
              </div>
            </div>

            <hr />

            {{-- BAGIAN DETAIL KEGIATAN KONTRAK  --}}
            <h4 class="text-primary font-weight-bold">Detail Kegiatan</h4>

            <div id="detail-wrapper">

              @php
                $oldDetail = old('detail', [
                    [
                        'id_akun' => '',
                        'id_sub_akun' => '',
                        'id_kegiatan' => '',
                        'jumlah_target_dokumen' => '',
                        'jumlah_dokumen' => '',
                    ],
                ]);
              @endphp
              @foreach ($oldDetail as $i => $d)
                <div class="detail-item border p-2 rounded mb-3" data-old-sub="{{ $d['id_sub_akun'] ?? '' }}"
                  data-old-keg="{{ $d['id_kegiatan'] ?? '' }}">

                  {{-- akun  --}}
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

                    {{-- subakun  --}}
                    <div class="col-md-6">
                      <select name="detail[{{ $i }}][id_sub_akun]" class="custom-select sub-akun-select">
                        <option value="">-- Pilih Sub Akun --</option>
                      </select>
                      @error("detail.$i.id_sub_akun")
                        <x-input-validation>{{ $message }}</x-input-validation>
                      @enderror
                    </div>
                  </div>

                  {{-- kegiatan  --}}
                  <div class="row mb-2">
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

            <button type="button" id="addDetail" class="btn btn-primary btn-sm">+ Tambah Kegiatan</button>


            <div class="mt-2">
              <a href="{{ route('kontrak.index') }}">
                <button type="button" class="btn btn-secondary">Kembali</button>
              </a>
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

      $('#addDetail').click(function() {
        let clone = $('.detail-item:first').clone();

        clone.find('select, input').each(function() {
          let name = $(this).attr('name');
          name = name.replace(/\[\d+\]/, '[' + index + ']');
          $(this).attr('name', name).val('');
        });

        clone.find('.remove-item').removeClass('d-none');
        clone.find('.sub-akun-select').prop('disabled', true).html('<option>-- Pilih Sub Akun --</option>');
        clone.find('.kegiatan-select').prop('disabled', true).html('<option>-- Pilih Kegiatan --</option>');

        $('#detail-wrapper').append(clone);
        index++;
      });

      $(document).on('click', '.remove-item', function() {
        $(this).closest('.detail-item').remove();
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
            subSelect.html('<option>-- Pilih Sub Akun --</option>');
            res.forEach(function(r) {
              subSelect.append(
                `<option value="${r.id}">${r.kode_sub_akun} - ${r.nama_sub_akun}</option>`);
            });
            subSelect.prop('disabled', false);

            let oldSub = parent.attr('data-old-sub');
            if (oldSub) {
              subSelect.val(oldSub).trigger('change');
            }
          });
        }
      });

      $(document).on('change', '.sub-akun-select', function() {
        let parent = $(this).closest('.detail-item');
        let subID = $(this).val();
        let kegSelect = parent.find('.kegiatan-select');

        kegSelect.html('<option>Loading...</option>').prop('disabled', true);

        if (subID) {
          $.get('/ajax/kegiatan/' + subID, function(res) {
            kegSelect.html('<option>-- Pilih Kegiatan --</option>');
            res.forEach(function(r) {
              kegSelect.append(
                `<option value="${r.id}">${r.kode_akun_kegiatan} - ${r.nama_kegiatan}</option>`);
            });
            kegSelect.prop('disabled', false);

            let oldKeg = parent.attr('data-old-keg');
            if (oldKeg) {
              kegSelect.val(oldKeg);
            }
          });
        }
      });

      // trigger old value untuk semua detail kegiatan
      $('.detail-item').each(function() {
        let oldAkun = $(this).find('.akun-select').val();
        if (oldAkun) {
          $(this).find('.akun-select').trigger('change');
        }
      });

    });
  </script>
@endsection
