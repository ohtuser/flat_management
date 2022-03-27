<script>
    $(".form_submit").submit(function(e){
        console.log("weewewe");
        e.preventDefault();
        var customConfig={
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
        };
        var formData = new FormData(this);
        getLoadingStatus();
        customAjaxCall(function (res)
        {
            customSweetAlert(function(){
                // console.log(res);
                if(res.redirectTo == 'close' && res.call != ''){
                    closeSweetAlert();
                    window[res.call]();
                    // call(res.call);
                    $('.form_submit').trigger('reset');
                    console.log("called",res.call);
                }else if(res.redirectTo == 'close'){
                    closeSweetAlert();
                }
                else if(res.redirectTo == 'closeAndModalHide'){
                    closeSweetAlert();
                    $('.modal').modal('hide');
                }
                else if(res.redirectTo == 'reload'){
                    location.reload();
                }
                else{
                    closeSweetAlert();
                    window.location.href=res.redirectTo;
                }
                form_submit_reset();
            }, 'success',res.message,res.description,res.buttonShow,null,res.timer);
        },'POST',$(this).attr('action'),formData,customConfig);
    });

    function getLoadingStatus(title="Loading", html="Please Wait"){
        Swal.fire({
            title: title,
            html: html,
            didOpen: () => {
                Swal.showLoading()
            }
        })
    }

    function closeSweetAlert(){
        Swal.close();
    }

    function customSweetAlert(callback,icon='success', title='Added', html='Data Inserted Successfully',showConfirmButton=false,position='center',timer=null,nowait=false){
        // setTimeout(function() {
            Swal.fire({
                icon: icon,
                title: title,
                html: html,
                showConfirmButton: showConfirmButton,
                position: position,
                timer: timer,
            }).then(()=> {
                callback();
            });
        // }, 1000);
        // if(nowait){
        //     callback();
        // }
    }

    async function customAjaxCall(callback, method, url, data,customConfig) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var defConfig={
            type: method,
            dataType: 'json',
            url: url,
            data: data,
            success: function (data) {
                callback(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                callback(-1);
                if (jqXHR.status === 0) {
                    customSweetAlert(function(){
                        console.log(textStatus);
                    }, 'error', 'Oppps!', 'Not connect. Verify Network.',true,null,null);
                } else if (jqXHR.status == 404) {
                    customSweetAlert(function(){
                        console.log(textStatus);
                    }, 'error', 'Oppps!', 'Requested page not found.',true,null,null);
                } else if (jqXHR.status == 500) {
                    customSweetAlert(function(){
                        console.log(textStatus);
                    }, 'error', 'Oppps!', 'Internal Server Error.',true,null,null);
                }else if (jqXHR.status == 422) {
                    var object =  jqXHR.responseJSON.errors,
                    result = Object.keys(object).reduce(function (r, k) {
                        return r.concat(object[k]+'<br>');
                    }, []);
                    customSweetAlert(function(){
                        console.log(jqXHR);
                    }, 'error', 'Oppps!', result.join(""),true,null,null);

                } else if (jqXHR.status == 421) {
                    customSweetAlert(function(){
                        console.log(jqXHR);
                    }, 'error', 'Oppps!', jqXHR.responseJSON.message,true,null,null);
                }else if (errorThrown === 'timeout') {
                    customSweetAlert(function(){
                        console.log(textStatus);
                    }, 'error', 'Oppps!', 'Time out error.',true,null,null);
                } else if (errorThrown === 'abort') {
                    customSweetAlert(function(){
                        console.log(textStatus);
                    }, 'error', 'Oppps!', 'Request aborted.',true,null,null);
                } else {
                    customSweetAlert(function(){
                        console.log(textStatus);
                        console.log(jqXHR);
                    }, 'error', 'Oppps!', 'Uncaught Error.',true,null,null);
                }
            }
        };
        if(typeof customConfig != 'undefined'){
            defConfig = Object.assign({}, defConfig, customConfig);
        }
        $.ajax(defConfig);
    }

    $(document).ready(function(){
        $('.select_2').select2();

        $(document).on('click', '.pagination a', function (e) {
            getLoader();
            e.preventDefault();
            customAjaxCall(function(res){
                $('.commonListBody').html(res.body);
                $('.commonListPaginate').html(res.paginate);
            },'get',$(this).attr('href'))
        });
    });

    function getLoader() {
        $('.commonListBody').html(`<div class="d-flex justify-content-center">
            <div class="spinner-border" style="color: #17a2b8!important" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>`);
    }

    function closeLoader(){
        $('defaultLoader').hide();
    }

    function form_submit_reset(){
        $('.form_submit').trigger('reset');
        $('.form_submit select').trigger('change');
        $('.row_id').val('');
    }
</script>
