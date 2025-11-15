@extends('layouts.template')
@section('content')
  <div class="col-md-12">
    <div class="card card-primary">

      <div class="card-body">
        <form method="POST" action="{{ route('kontrak.store') }}">
          @csrf

          <div class="row">
            <div class="col-md-12">

              <h4 class="text-primary font-weight-bold">Data Kontrak</h4>

              <div class="form-row">
                {{-- BAGIAN DATA KONTRAK UTAMA  --}}

                {{-- mitra  --}}
                <div class="form-group col-md-6">
                  <label for="mitra_id">Mitra</label>
                  <select class="form-control select2 @error('mitra_id') is-invalid @enderror" id="mitra_id"
                    name="mitra_id" style="width: 100%;">
                    <option value="" disabled {{ old('mitra_id') ? '' : 'selected' }}>
                      -- Silahkan pilih mitra --
                    </option>
                    @foreach ($mitra as $row)
                      <option value="{{ $row->id }}" {{ old('mitra_id') == $row->id ? 'selected' : '' }}>
                        {{ $row->nms }} - {{ $row->nama_lengkap }}
                      </option>
                    @endforeach
                  </select>
                  @error('mitra_id')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>

                {{-- periode  --}}
                <div class="form-group col-md-6">
                  <label for="periode" id="periode">Periode</label>
                  <input type="month" name="periode" id="periode"
                    class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode') }}"
                    onclick="this.showPicker()" />
                  @error('periode')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>

                {{-- tgl kontrak --}}
                <div class="form-group col-md-4">
                  <label for="tanggal_kontrak" id="tanggal_kontrak">Tanggal Kontrak</label>
                  <input type="date" name="tanggal_kontrak" id="tanggal_kontrak"
                    class="form-control @error('tanggal_kontrak') is-invalid @enderror"
                    value="{{ old('tanggal_kontrak') }}" onclick="this.showPicker()" />
                  @error('tanggal_kontrak')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>

                {{-- tgl bast  --}}
                <div class="form-group col-md-4">
                  <label for="tanggal_bast" id="tanggal_bast">Tanggal BAST
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
                  <label for="tanggal_surat" id="tanggal_surat">Tanggal Surat</label>
                  <input type="date" name="tanggal_surat" id="tanggal_surat"
                    class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat') }}"
                    onclick="this.showPicker()" />
                  @error('tanggal_surat')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>

                {{-- tgl mulai  --}}
                <div class="form-group col-md-6">
                  <label for="tanggal_mulai" id="tanggal_mulai">Tanggal Mulai</label>
                  <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                    class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}"
                    onclick="this.showPicker()" />
                  @error('tanggal_mulai')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>

                {{-- tgl berakhir  --}}
                <div class="form-group col-md-6">
                  <label for="tanggal_berakhir" id="tanggal_berakhir">Tanggal Berakhir</label>
                  <input type="date" name="tanggal_berakhir" id="tanggal_berakhir"
                    class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                    value="{{ old('tanggal_berakhir') }}" onclick="this.showPicker()" />
                  @error('tanggal_berakhir')
                    <x-input-validation>{{ $message }}</x-input-validation>
                  @enderror
                </div>

                {{-- keterangan  --}}
                <div class="form-group col-md-12">
                  <label for="keterangan" id="keterangan">Keterangan (opsional)</label>
                  <textarea name="keterangan" id="keterangan" placeholder="Masukkan keterangan..." class="form-control" cols="30"
                    rows="2">{{ old('keterangan') }}</textarea>
                </div>


                {{-- BAGIAN DETAIL  --}}
              </div>
            </div>

            <div class="mt-2">
              <a href="{{ route('kontrak.index') }}">
                <button type="button" class="btn btn-secondary">Kembali</button>
              </a>
              <button type="submit" class="btn btn-primary">Simpan data</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(function() {
      $('.select2').select2()
    })
  </script>
@endsection
