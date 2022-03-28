@extends('layouts.master')

@section('content')
    <div class="card mt-2">
        <div class="card-header bg-dark text-light">
            <div class="d-flex justify-content-between">
                <h6>Building Transactions</h6>
                {{-- <a href="{{route('building_transactions',['id'=>$buildingInfos[0]->building_id,'month'=>date('m'),'year'=>date('Y')])}}" class="btn btn-success btn-sm">Transactions</a> --}}
            </div>
        </div>
        <div class="card-body">
            @php
                $transactionInfo = collect($transactionInfo);
                $monthArr = ['', 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            @endphp
             <form action="{{route('building_transactions')}}" method="get">
            <div class="row">

                    <input type="hidden" name="id" value="{{request()->id}}">
                <div class="col-1">
                    <select name="year" class="form-control select_2">
                        @for($i=2022;$i<=2025;$i++)
                            <option @if(request()->year == $i) selected @endif value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-1">
                    <select name="month" class="form-control select_2">
                        @for($i=1;$i<=12;$i++)
                            <option @if(request()->month == $i) selected @endif value="{{$i}}">{{$monthArr[$i]}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-1">
                    <label for="">&nbsp;</label>
                    <button class="btn btn-info btn-sm">Filter</button>
                </div>

            </div>
        </form>
            @foreach (collect($buildingInfos)->groupBy('floor') as $floor)
                <div class="row mt-1">
                    <h4>Floor: {{$floor[0]->floor}}</h4>
                    @foreach ($floor as $flat)
                        @php
                            $is_rented = $transactionInfo->where('flat_id',$flat->id)->first();
                            // echo $is_rented;
                        @endphp
                        <div class="col-2">
                            <div class="flat text-light text-center rounded py-2 {{$is_rented ? $is_rented->pay == $is_rented->rent ? 'bg-success' : 'bg-warning' : 'bg-dark'}}">
                                <h5 class="mb-0">{{ $flat->flat_no }}</h5>
                                <p class="mb-0">{{$is_rented ? 'Rent: '.$is_rented->rent : 'E.Rent: '. $flat->rent}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

        </div>
    </div>
@endsection

@section('js')

    <script>
        $('.select_2').select2();
    </script>

@endsection
