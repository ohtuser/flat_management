@extends('layouts.master')

@section('content')
    <div class="card mt-2">
        <div class="card-header bg-dark text-light">
            <div class="d-flex justify-content-between">
                <h6>Building Transaction Report</h6>
                <a href="{{route('building_transactions',['id'=>$buildingInfos[0]->building_id,'month'=>request()->month,'year'=>request()->year])}}" class="btn btn-success btn-sm">Transactions</a>
            </div>
        </div>
        <div class="card-body">
            @php
                $transactionInfo = collect($transactionInfo);
                $monthArr = ['', 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            @endphp
             <div class="row">
                 <div class="col-6">
                    <form action="{{route('building_transactions',['report'=>1])}}" method="get">
                        <div class="row">
                            <input type="hidden" name="id" value="{{request()->id}}">
                            <input type="hidden" name="report" value="1">
                            <div class="col">
                                <select name="year" class="form-control select_2">
                                    @for($i=2022;$i<=2025;$i++)
                                        <option @if(request()->year == $i) selected @endif value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col">
                                <select name="month" class="form-control select_2">
                                    @for($i=1;$i<=12;$i++)
                                        <option @if(request()->month == $i) selected @endif value="{{$i}}">{{$monthArr[$i]}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col">
                                <label for="">&nbsp;</label>
                                <button class="btn btn-info btn-sm">Filter</button>
                            </div>

                        </div>
                    </form>
                 </div>
             </div>
             <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Floor</th>
                        <th>Flat No</th>
                        <th>Renter</th>
                        <th>Rent</th>
                        <th>Pay</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach ($buildingInfos as $key=>$bi)
                        @php
                            $is_rented = $transactionInfo->where('flat_id',$bi->id)->first();
                        @endphp
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $bi->floor }}</td>
                            <td>{{ $bi->flat_no }}</td>
                            <td>{{ $is_rented ? get_renter_info($is_rented->tenant_id)->name : '-' }}</td>
                            <td>{{ $is_rented ? $is_rented->rent : 'E.Rent: '. $bi->rent }}</td>
                            <td>{{ $is_rented ? $is_rented->pay : '' }}</td>
                            <td>
                                @if($is_rented)
                                    @if($is_rented->rent == $is_rented->pay)
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-danger">Due</span>
                                    @endif
                                @else
                                    <span class="badge bg-dark">Vacant</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
             </table>
        </div>
    </div>
@endsection

@section('js')

@endsection
