@extends('adminlte::page')

@section('title', $kpiForm->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>KPI Form</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">KPI Form</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center">{{ucwords($kpiForm->name)}}</h3>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Description: </b> <a class="float-right">{{$kpiForm->description}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Assessment From:</b>
                                <a class="float-right">
                                    @foreach($kpiForm->assessment_from as $assessment_from)
                                        <span class="badge badge-info badge-sm">{{$assessment_from}}</span>
                                    @endforeach
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Assessment To</b> <a class="float-right"><span class="badge badge-info badge-sm">{{$kpiForm->assessment_to}}</span></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        @if(auth()->user()->can('add kpi'))
                            <button class="btn btn-primary btn-sm" id="add-kpi-btn">Add</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <table id="item-list" class="table table-bordered table-hover" role="grid" style="width: 100%">
                                <thead>
                                <tr role="row">
                                    <th style="width: 15%;">Date Added</th>
                                    <th style="width: 30%;">Item</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Unit Cost</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>


    <div class="modal fade kpi-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <form>

                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-gray-light">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-12 item_name">
                                <label for="item_name">Item Name</label><span class="required">*</span>
                                <input type="text" class="form-control" name="item_name" id="item_name" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 quantity">
                                <label for="quantity">Quantity</label><span class="required">*</span>
                                <input type="text" class="form-control" name="quantity" id="quantity" />
                            </div>
                            <div class="form-group col-lg-6 unit">
                                <label for="unit">Unit</label>
                                <input type="text" class="form-control" name="unit" id="unit" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 unit_price">
                                <label for="unit_price">Unit Price</label>
                                <input type="number" step="any" class="form-control" name="unit_price" id="unit_price" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop
@section('plugins.Sweetalert2',true)
@section('css')

@stop

@push('js')
    <script src="{{asset('js/clear_errors.js')}}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        $(function(){
            $('#item-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all-items') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'item_name', name: 'item_name'},
                    { data: 'quantity', name: 'quantity'},
                    { data: 'unit', name: 'unit'},
                    { data: 'unit_price', name: 'unit_price'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
            });
        });

        let kpiModal = $('.kpi-modal');
        let kpiId = '';
        let kpiTable = $('#item-list');

        function remove_required_field_errors()
        {
            kpiModal.find('.is-invalid').removeClass('is-invalid');
            kpiModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            kpiModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            kpiModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            kpiModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            kpiModal.find('button[type="submit"]').text('Save');
        }

        @can('add kpi')

        $(document).on('click','#add-kpi-btn', function(){
            kpiId = '';
            kpiModal.modal('toggle');
            kpiModal.find('.modal-title').text('Add KPI');
            kpiModal.find('form').attr('id','add-kpi-form');
            kpiModal.find('form').trigger('reset');
            remove_required_field_errors();
        });

        $(document).on('submit','#add-kpi-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/kpi',
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    action_before_form_submission();
                }
            }).done(function(response){
                if(response.success === true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    kpiTable.DataTable().ajax.reload(null, false);
                    kpiModal.find('form').trigger('reset');
                }else{
                    Toast.fire({
                        icon: "danger",
                        title: response.message
                    });
                }

            }).fail(function(xhr, status, error){

                $.each(xhr.responseJSON.errors, function(key, value){
                    kpiModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    kpiModal.find('#'+key).addClass('is-invalid');
                });

            }).always(function(){
                action_after_form_submission();
            })
        })
        @endcan

        @can('edit kpi')
        let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

        $(document).on('click','.edit-item-btn', function(){
            kpiModal.modal('toggle');
            kpiModal.find('.modal-title').text('Edit Item');
            kpiModal.find('form').attr('id','edit-item-form');

            kpiId = this.id;

            $.ajax({
                url: '/kpi/'+kpiId+'/edit',
                type: 'get',
                beforeSend: function(){
                    kpiModal.find('.modal-header').prepend(overlay);
                    remove_required_field_errors();
                }
            }).done(function(response){
                $.each(response, function(key, value){
                    kpiModal.find('input[name='+key+'], textarea[name='+key+']').val(value);
                });
            }).fail(function(xhr, status, error){
                console.log(xhr);
            }).always(function(){
                kpiModal.find('.overlay').remove();
            });
        });


        $(document).on('submit','#edit-kpi-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/kpi/'+kpiId,
                type: 'put',
                data: data,
                dataType: 'json',
                beforeSend: function(){
                    kpiModal.find('.is-invalid').removeClass('is-invalid');
                    kpiModal.find('.text-danger').remove();
                    kpiModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                    kpiModal.find('button[type="submit"]').text('Saving...');
                }
            }).done(function(response){
                console.log(response)
                if(response.success === true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    kpiTable.DataTable().ajax.reload(null, false);
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: response.message
                    });
                }
            }).fail(function(xhr, status, error){

                $.each(xhr.responseJSON.errors, function(key, value){
                    kpiModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    kpiModal.find('#'+key).addClass('is-invalid');
                });
            }).always(function(){
                kpiModal.find('.overlay').remove();
                action_after_form_submission();
            });
        });
        @endcan

        @can('delete kpi')

        $(document).on('click','.delete-item-btn', function(){
            kpiId = this.id;

            let tr = $(this).closest('tr');

            let data = tr.children("td").map(function () {
                return $(this).text();
            }).get();

            Swal.fire({
                title: `Delete ${data[1]}?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        'url' : '/item/'+itemId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            if(response.success === true){
                                kpiTable.DataTable().ajax.reload(null, false);

                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                            }
                        },error: function(xhr, status, error){
                            console.log(xhr);
                        }
                    });

                }
            });
        });

        @endcan
    </script>
@endpush


