@extends('layouts.template')
@section('content')
  <div class="row">

    {{-- button  --}}
    <div class="col-12">
      <a href="{{ route('kontrak.create') }}" class="btn btn-success mb-3">Tambah kontrak</a>
    </div>

    {{-- flashdata --}}
    <x-alert />


    <div class="col-12">
    </div>

  </div>
@endsection
