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
                <div class="form-group col-md-6">
                  <label for="mitra_id">Mitra</label>
                  <select class="form-control select2" id="mitra_id" name="mitra_id" style="width: 100%;">
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

                <hr />
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
