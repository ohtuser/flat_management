@extends('layouts.master')

@section('content')
    <div class="card mt-2">
        <div class="card-header bg-dark text-light">
            Building Info
        </div>
        <div class="card-body">

            @foreach (collect($buildingInfos)->groupBy('floor') as $floor)
                <div class="row mt-1">
                    <h4>Floor: {{$floor[0]->floor}}</h4>
                    @foreach ($floor as $flat)
                        <div class="col-2">
                            <div class="flat bg-dark text-light text-center rounded py-2">
                                <h5 class="mb-0">{{ $flat->flat_no }}</h5>
                                <p class="mb-0">Rent: {{ $flat->rent }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

        </div>
    </div>
@endsection
