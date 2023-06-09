@extends('layout.main')

@section('content')

<div class="w-4/12 p-4">
  {{ $data->onEachSide(5)->links() }}
</div>
@endsection
