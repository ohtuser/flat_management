@extends('layouts.master')

@section('content')
    <div class="card mt-2">
        <div class="card-header bg-dark text-light">
            <div class="d-flex justify-content-between">
                <h6>Building Transactions</h6>
                <a href="{{route('building_transactions',['id'=>$buildingInfos[0]->building_id,'month'=>request()->month,'year'=>request()->year,'report'=>1])}}" class="btn btn-success btn-sm">Transactions Report</a>
            </div>
        </div>
        <div class="card-body">
            @php
                $transactionInfo = collect($transactionInfo);
                $monthArr = ['', 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            @endphp
             <div class="row">
                 <div class="col-6">
                    <form action="{{route('building_transactions')}}" method="get">
                        <div class="row">
                            <input type="hidden" name="id" value="{{request()->id}}">
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
                 <div class="col-6">
                    <form action="{{route('building_transactions.import')}}" method="post" class="form_submit">
                        <div class="row">
                            <input type="hidden" name="id" value="{{request()->id}}">
                            <input type="hidden" name="year" value="{{request()->year}}">
                            <input type="hidden" name="month" value="{{request()->month}}">
                            <div class="col">
                                <select name="from_year" class="form-control select_2">
                                    @for($i=2022;$i<=2025;$i++)
                                        <option @if(request()->year == $i) selected @endif value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col">
                                <select name="from_month" class="form-control select_2">
                                    @for($i=1;$i<=12;$i++)
                                        <option @if(request()->month == $i) selected @endif value="{{$i}}">{{$monthArr[$i]}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col">
                                <label for="">&nbsp;</label>
                                <button class="btn btn-danger btn-sm">Import</button>
                            </div>

                        </div>
                    </form>
                 </div>
             </div>
            @foreach (collect($buildingInfos)->groupBy('floor') as $floor)
                <div class="row mt-1">
                    <h4>Floor: {{$floor[0]->floor}}</h4>
                    @foreach ($floor as $flat)
                        @php
                            $is_rented = $transactionInfo->where('flat_id',$flat->id)->first();
                        @endphp
                        <div class="col-2">
                            <div class="flat text-light text-center rounded py-2 {{$is_rented ? $is_rented->pay == $is_rented->rent ? 'bg-success' : 'bg-custom-blue' : 'bg-dark'}}">
                                <h5 class="mb-0">{{ $flat->flat_no }}</h5>
                                <p class="mb-0">{{$is_rented ? 'Rent: '.$is_rented->rent : 'E.Rent: '. $flat->rent}} {{$is_rented ? ($is_rented->rent-$is_rented->pay > 0 ? ('Due: '.$is_rented->rent-$is_rented->pay) : '') : ''}}</p>
                                @if($is_rented)

                                    @php
                                        $info = get_renter_info($is_rented->tenant_id);
                                    @endphp
                                    <p class="mb-0">{{($info != null ? $info->name : '')}}</p>
                                    @if($is_rented->pay == $is_rented->rent)
                                        Paid
                                    @else
                                        <button class="btn btn-xs btn-dark" onclick="makePayment({{$is_rented->id}},{{floatVal($is_rented->rent-$is_rented->pay)}})">Make Payment</button>
                                        <button class="btn btn-xs btn-warning" onclick="editRenter({{$is_rented->tenant_id}},{{$is_rented->id}},{{floatVal($is_rented->rent)}},{{floatVal($is_rented->pay)}})">Edit</button>
                                    @endif
                                @else
                                    <p class="mb-0">&nbsp;</p>
                                    <button class="btn btn-xs btn-danger" onclick="addRenter({{$flat->id}},{{$flat->building_id}})">Add Renter</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>


    <div class="modal fade" id="addRenter" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="addRenterLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addRenterLabel">Add Renter</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('flat_rent')}}" class="form_submit">
                <div class="modal-body">
                    <input type="hidden" name="year" value="{{request()->year}}">
                    <input type="hidden" name="month" value="{{request()->month}}">
                    <input type="hidden" name="flat_id" value="" class="flat_id">
                    <input type="hidden" name="building_id" value="" class="building_id">
                    <div class="form-group">
                        <label for="">Renter</label>
                        <select name="renter" class="form-control py-0" id="">
                            @foreach ($renters as $r)
                                <option value="{{$r->id}}">{{$r->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Rent</label>
                        <input type="number" name="rent" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Pay</label>
                        <input type="number" name="pay" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
          </div>
        </div>
    </div>

    <div class="modal fade" id="editRenter" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="editRenterLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editRenterLabel">Edit Renter</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update_flat_rent')}}" class="form_submit">
                <div class="modal-body">
                    <input type="hidden" name="transaction_id" value="" class="transaction_id">
                    <div class="form-group">
                        <label for="">Renter</label>
                        <select name="renter" class="form-control py-0" id="" class="renter">
                            @foreach ($renters as $r)
                                <option value="{{$r->id}}">{{$r->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Rent</label>
                        <input type="number" name="rent" class="form-control edi_rent">
                    </div>
                    <div class="form-group">
                        <label for="">Pay</label>
                        <input type="number" name="pay" class="form-control edit_pay">
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
          </div>
        </div>
    </div>

    <div class="modal fade" id="makePayment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="makePaymentLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="makePaymentLabel">Make Payment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('make_payment')}}" class="form_submit">
                <input type="hidden" name="transaction_id" value="" class="transaction_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Pay</label>
                        <input type="number" name="pay" class="form-control pay_amount">
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
          </div>
        </div>
    </div>
@endsection

@section('js')

    <script>
        $('.select_2').select2();


        function addRenter(id,building){
            console.log("ewewe");
            $('.flat_id').val(id);
            $('.building_id').val(building);
            $('#addRenter').modal('show');
        }

        function editRenter(tenant,id,rent,pay){
            $('.renter').val(tenant);
            $('.transaction_id').val(id);
            $('.edit_pay').val(pay);
            $('.edi_rent').val(rent);
            $('#editRenter').modal('show');
        }

        function makePayment(id,due){
            console.log(id,due);
            $('#makePayment').modal('show');
            $('.transaction_id').val(id);
            $('.pay_amount').val(due);
        }
    </script>

@endsection
