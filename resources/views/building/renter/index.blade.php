@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-4">
            <div class="card mt-2">
                <div class="card-header bg-dark text-light">
                    Add Renter
                </div>
                <div class="card-body">
                    <form action="{{ route('renter.store') }}" class="form_submit">
                        <div class="row">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="form-group">
                                <label for="">Number Of Family Member</label>
                                <input type="number" min="1" class="form-control number_of_floors" name="number_of_family_member">
                            </div>
                            <div class="form-group">
                                <label for="">From (Expected)</label>
                                <input type="number" min="1" class="form-control date_picker" name="date">
                            </div>
                            <div class="form-group">
                                <label for="">Advance Amount</label>
                                <input type="number" min="1" class="form-control" name="adv_amount">
                            </div>
                            {{-- <div class="form-group">
                                <label for="">NID</label>
                                <input type="file" class="form-control" name="nid">
                            </div>
                            <div class="form-group">
                                <label for="">Agreement</label>
                                <input type="file" class="form-control" name="agreement">
                            </div> --}}
                            <div class="form-group">
                                <label for="">&nbsp;</label><br>
                                <button class="btn btn-success btn-sm">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card mt-2">
                <div class="card-header bg-dark text-light">
                    Renters
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>N.F.M.</th>
                                <th>From</th>
                                <th>Advance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($renters as $key=>$r)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$r->name}}</td>
                                    <td>{{$r->no_of_family_members}}</td>
                                    <td>{{date('d-m-Y', strtotime($r->start_month_year))}}</td>
                                    <td>{{ $r->advance_amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
