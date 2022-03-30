@extends('layouts.master')

@section('css')

    <style>
        .bg-custom-secondary{
            background-color: #f0f0f0;
        }
    </style>
@endsection
@section('content')
<form action="{{route('building_store')}}" class="form_submit">  {{-- class="form_submit" --}}
    @csrf
    <div class="card mt-2">
        <div class="card-header bg-dark text-light">
            Add Building
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" name="building_name">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Floors</label>
                    <input type="number" min="1" class="form-control number_of_floors" name="number_of_floors">
                </div>
                <div class="col-3">
                    <label for="">Address</label>
                    <textarea name="address" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <div class="col-1">
                    <label for="">&nbsp;</label><br>
                    <button type="button" class="btn btn-success btn-sm load_floors">Next</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-2 flat_list_container" style="display: none">
        <div class="card-header bg-dark text-light">
            Add Flats And Rent
        </div>
        <div class="card-body flat_list py-0">

        </div>
        <div class="d-flex justify-content-center">
            <button class="btn btn-success btn-sm" type="submit" style="width: 100px">Save</button>
        </div>
    </div>
</form>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            let flat_list = '';
            $('.load_floors').click(function() {
                let number_of_floors = $('.number_of_floors').val();

                if(number_of_floors == '' || number_of_floors == 0){
                    $.confirm({
                        title: 'Number Of Floors Missing.'
                    });
                    return false;
                }
                console.log(number_of_floors);
                $.confirm({
                    title: 'Are You Sure?',
                    content: '',
                    type: 'green',
                    buttons: {
                        ok: {
                            text: "ok!",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function() {
                                $('.number_of_floors').attr('readonly',true);
                                for(i=1;i<=number_of_floors;i++){
                                    flat_list +=
                                    `<div class="row flat_list${i} ${i%2==0 ? '' : 'bg-custom-secondary'} py-3" >
                                        <div class="col-2 d-flex justify-content-between">
                                            <div class="form-group">
                                                <label>Floor Name</label>
                                                <input class="form-control" type="text" value="${floorrName(i)}" name="floor[${i}]">
                                            </div>
                                            <div>
                                                <label>&nbsp</label><br>
                                                <button type="button" class="btn btn-success btn-sm" onclick="addFlat(${i})">+</button>
                                            </div>
                                        </div>
                                        <div class="col-10 flat_list_inner${i}">
                                            <div class="row">

                                            </div>
                                        </div>
                                    </div>`
                                }

                                $('.flat_list').html(flat_list);
                                $('.flat_list_container').show('slow');
                            }
                        },
                        cancel: function() {
                            console.log('the user clicked cancel');
                        }
                    }
                });
            });



        });

        function floorrName(n) {
            if(n==1){
                return "Ground";
            }
            n=n-1;
            var s = ["th", "st", "nd", "rd"],
                v = n % 100;

            return n + (s[(v - 20) % 10] || s[v] || s[0]);
        }
        function addFlat(row){
            $(`.flat_list_inner${row} .row`).append(`
                <div class="col-2">
                    <div class="form-group">
                        <label>Flat No.</label>
                        <input type="text" class="form-control" name="flat_no[${row}][]">
                    </div>
                    <div class="form-group">
                        <label>Rent</label>
                        <input type="text" class="form-control" name="rent[${row}][]">
                    </div>
                </div>
            `);
        }
    </script>
@endsection
