@extends('layouts.master')

@section('content')
    <div class="card mt-2">
        <div class="card-header bg-dark text-light">
            Add Building
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Floors</label>
                    <input type="number" min="1" class="form-control number_of_floors">
                </div>
                <div class="col-3">
                    <label for="">Address</label>
                    <textarea name="address" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <div class="col-1">
                    <label for="">&nbsp;</label><br>
                    <button class="btn btn-success btn-sm load_floors">Next</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
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
                    content: 'dfdf',
                    type: 'green',
                    buttons: {
                        ok: {
                            text: "ok!",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function() {

                            }
                        },
                        cancel: function() {
                            console.log('the user clicked cancel');
                        }
                    }
                });
            });
        });
    </script>
@endsection
