@extends('adminlte::page')

@section('title', 'Supplier Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>Supplier Profile</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item"><a href="{{route('supplier.index')}}">Suppliers</a> </li>
                <li class="breadcrumb-item active">Supplier Profile</li>
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
                        <h3 class="profile-username text-center">{{ucwords($supplier->company_name)}}</h3>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Email: </b> <a class="float-right">{{$supplier->email}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Mobile No.:</b> <a class="float-right">{{$supplier->mobile_number}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Telephone</b> <a class="float-right">{{$supplier->telephone}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Location</b> <a class="float-right">{{ucwords($supplier->company_address)}}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#items" data-toggle="tab">Items</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="items">

                                <div class="btn-group mb-4" role="group" aria-label="Button group with nested dropdown">

                                    @can('add item')
                                        <button type="button" class="btn btn-primary" id="add-item-btn">Add</button>
                                    @endcan

                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-caret-down" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            @can('upload item')
                                                <a class="dropdown-item" href="#" id="upload-item-btn" title="Upload">Import Items</a>
                                            @endcan
                                            @can('upload item')
                                                <a class="dropdown-item" href="#" id="download-item-btn" title="Download">Download template</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>

                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <table id="item-list" class="table table-bordered table-hover" role="grid" style="width: 100%">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 15%;">Date Added</th>
                                                <th style="width: 30%;">Item</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Unit Cost</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputName2" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>


    <div class="modal fade item-modal" tabindex="-1" role="dialog">
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
                    <input type="hidden" name="supplier_id" value="{{$supplier->id}}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade upload-item-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
{{--            <form id="upload-items-form" action="{{route('item.import')}}" method="post" enctype="multipart/form-data">--}}
            <form id="upload-items-form" enctype="multipart/form-data">

                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-gray-light">
                        <h5 class="modal-title">Import Items</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group import_item">
                            <input type="file" name="import_item" id="import_item">
                        </div>
                    </div>
                    <input type="hidden" name="supplier_id" value="{{$supplier->id}}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary import-btn">Import</button>
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
                ajax: '{!! route('all-items',['supplierId' => $supplier->id]) !!}',
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

        let itemModal = $('.item-modal');
        let itemId = '';
        let itemTable = $('#item-list');

        function remove_required_field_errors()
        {
            itemModal.find('.is-invalid').removeClass('is-invalid');
            itemModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            itemModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            itemModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            itemModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            itemModal.find('button[type="submit"]').text('Save');
        }

        @can('add item')

            $(document).on('click','#add-item-btn', function(){
                itemId = '';
                itemModal.modal('toggle');
                itemModal.find('.modal-title').text('Add Item');
                itemModal.find('form').attr('id','add-item-form');
                itemModal.find('form').trigger('reset');
                remove_required_field_errors();
            });

        $(document).on('submit','#add-item-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/item',
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
                    itemTable.DataTable().ajax.reload(null, false);
                    itemModal.find('form').trigger('reset');
                }else{
                    Toast.fire({
                        icon: "danger",
                        title: response.message
                    });
                }

            }).fail(function(xhr, status, error){

                $.each(xhr.responseJSON.errors, function(key, value){
                    itemModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    itemModal.find('#'+key).addClass('is-invalid');
                });

            }).always(function(){
                action_after_form_submission();
            })
        })
        @endcan

        @can('edit item')
            let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

            $(document).on('click','.edit-item-btn', function(){
                itemModal.modal('toggle');
                itemModal.find('.modal-title').text('Edit Item');
                itemModal.find('form').attr('id','edit-item-form');

                itemId = this.id;

                $.ajax({
                    url: '/item/'+itemId+'/edit',
                    type: 'get',
                    beforeSend: function(){
                        itemModal.find('.modal-header').prepend(overlay);
                        remove_required_field_errors();
                    }
                }).done(function(response){
                    $.each(response, function(key, value){
                        itemModal.find('input[name='+key+'], textarea[name='+key+']').val(value);
                    });
                }).fail(function(xhr, status, error){
                    console.log(xhr);
                }).always(function(){
                    itemModal.find('.overlay').remove();
                });
            });


            $(document).on('submit','#edit-item-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '/item/'+itemId,
                    type: 'put',
                    data: data,
                    dataType: 'json',
                    beforeSend: function(){
                        itemModal.find('.is-invalid').removeClass('is-invalid');
                        itemModal.find('.text-danger').remove();
                        itemModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                        itemModal.find('button[type="submit"]').text('Saving...');
                    }
                }).done(function(response){
                    console.log(response)
                    if(response.success === true)
                    {
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                        itemTable.DataTable().ajax.reload(null, false);
                    }else{
                        Toast.fire({
                            icon: "warning",
                            title: response.message
                        });
                    }
                }).fail(function(xhr, status, error){

                    $.each(xhr.responseJSON.errors, function(key, value){
                        itemModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                        itemModal.find('#'+key).addClass('is-invalid');
                    });
                }).always(function(){
                    itemModal.find('.overlay').remove();
                    action_after_form_submission();
                });
            });
        @endcan

        @can('delete item')

        $(document).on('click','.delete-item-btn', function(){
            itemId = this.id;

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
                                itemTable.DataTable().ajax.reload(null, false);

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

        @can('upload item')
            let uploadItemModal = $('.upload-item-modal');
            $(document).on('click','#upload-item-btn', function(){
                uploadItemModal.modal('toggle');
            });

            $(document).on('submit','#upload-items-form', function(e){
                e.preventDefault();
                const form = this;
                const data = new FormData(form);

                console.log(data);

                $.ajax({
                    url: '/item-import',
                    method: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){
                        $('.upload-item-modal').find('.is-invalid').removeClass('is-invalid');
                        $('.upload-item-modal').find('.text-danger').remove();
                        $('.upload-item-modal').find('.modal-header').prepend(overlay);
                        $('#upload-items-form').find('.import-btn').attr('disabled',true).text('Importing...');
                    }
                }).done(function(response){
                    if(response.success === true)
                    {
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                        $('#upload-items-form').trigger('reset');
                        $('.upload-item-modal').modal('toggle');
                        itemTable.DataTable().ajax.reload(null, false);
                    }else{
                        Toast.fire({
                            icon: "warning",
                            title: response.message
                        });
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr);
                    $.each(xhr.responseJSON.errors, function(key, value){
                        $('.upload-item-modal').find('.'+key).append('<p class="text-danger">'+value+'</p>');
                        $('.upload-item-modal').find('#'+key).addClass('is-invalid');
                    });
                }).always(function(){
                    $('#upload-items-form').find('.import-btn').attr('disabled',false).text('Import');
                    $('.upload-item-modal').find('.overlay').remove();
                })
            })
        @endcan
    </script>
@endpush


