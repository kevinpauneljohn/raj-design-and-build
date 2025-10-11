@extends('adminlte::page')

@section('title', 'Items')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>Items</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">Items</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-secondary card-outline card-tabs">
        <div class="card-header">
            @can('add supplier')
                <button class="btn btn-sm btn-secondary" id="add-supplier-btn">Add</button>
            @endcan
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="item-list" class="table table-striped table-hover border border-2" role="grid" style="width: 100%">
                    <thead>
                    <tr role="row">
                        <th style="width: 15%;">Date Added</th>
                        <th>Supplier</th>
                        <th style="width: 30%;">Item</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Unit Cost</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade supplier-modal" tabindex="-1" role="dialog">
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
                            <div class="form-group col-lg-12 company_name">
                                <label for="company_name">Company Name</label><span class="required">*</span>
                                <input type="text" class="form-control" name="company_name" id="company_name" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 company_address">
                                <label for="company_address">Company Address</label><span class="required">*</span>
                                <textarea class="form-control" name="company_address" id=company_address"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 email">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 mobile_number">
                                <label for="mobile_number">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile_number" id="mobile_number" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 telephone">
                                <label for="telephone">Telephone No.</label>
                                <input type="text" class="form-control" name="telephone" id="telephone" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Save</button>
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
            $(function(){
                $('#item-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('all-supplier-items') !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at'},
                        { data: 'company_name', name: 'company_name'},
                        { data: 'item_name', name: 'item_name'},
                        { data: 'quantity', name: 'quantity'},
                        { data: 'unit', name: 'unit'},
                        { data: 'unit_price', name: 'unit_price'},
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 10,
                });
            });
        });

        let supplierModal = $('.supplier-modal');
        let supplierId = '';
        let supplierTable = $('#supplier-list');

        function remove_required_field_errors()
        {
            supplierModal.find('.is-invalid').removeClass('is-invalid');
            supplierModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            supplierModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            supplierModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            supplierModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            supplierModal.find('button[type="submit"]').text('Save');
        }

        @can('add supplier')
        $(document).on('click','#add-supplier-btn', function(){
            supplierId = '';
            supplierModal.modal('toggle');
            supplierModal.find('.modal-title').text('Add Supplier');
            supplierModal.find('form').attr('id','add-supplier-form');
            supplierModal.find('form').trigger('reset');
            remove_required_field_errors();
        });

        $(document).on('submit','#add-supplier-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/supplier',
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
                    supplierTable.DataTable().ajax.reload(null, false);
                    supplierModal.find('form').trigger('reset');
                }else{
                    Toast.fire({
                        icon: "danger",
                        title: response.message
                    });
                }

            }).fail(function(xhr, status, error){

                $.each(xhr.responseJSON.errors, function(key, value){
                    supplierModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    supplierModal.find('#'+key).addClass('is-invalid');
                });

            }).always(function(){
                action_after_form_submission();
            })
        })
        @endcan

        @can('edit supplier')
        let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

        $(document).on('click','.edit-supplier-btn', function(){
            supplierModal.modal('toggle');
            supplierModal.find('.modal-title').text('Edit Supplier');
            supplierModal.find('form').attr('id','edit-supplier-form');

            supplierId = this.id;

            $.ajax({
                url: '/supplier/'+supplierId+'/edit',
                type: 'get',
                beforeSend: function(){
                    supplierModal.find('.modal-header').prepend(overlay);
                    remove_required_field_errors();
                }
            }).done(function(response){
                $.each(response, function(key, value){
                    supplierModal.find('input[name='+key+'], textarea[name='+key+']').val(value);
                });
            }).fail(function(xhr, status, error){
                console.log(xhr);
            }).always(function(){
                supplierModal.find('.overlay').remove();
            });
        });


        $(document).on('submit','#edit-supplier-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/supplier/'+supplierId,
                type: 'put',
                data: data,
                dataType: 'json',
                beforeSend: function(){
                    supplierModal.find('.is-invalid').removeClass('is-invalid');
                    supplierModal.find('.text-danger').remove();
                    supplierModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                    supplierModal.find('button[type="submit"]').text('Saving...');
                }
            }).done(function(response){
                console.log(response)
                if(response.success === true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    supplierTable.DataTable().ajax.reload(null, false);
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: response.message
                    });
                }
            }).fail(function(xhr, status, error){
                console.log(xhr);

                $.each(xhr.responseJSON.errors, function(key, value){
                    supplierModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    supplierModal.find('#'+key).addClass('is-invalid');
                });
            }).always(function(){
                supplierModal.find('.overlay').remove();
                action_after_form_submission();
            });
        });
        @endcan


        @can('delete supplier')

        $(document).on('click','.delete-supplier-btn', function(){
            clientId = this.id;

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
                        'url' : '/supplier/'+clientId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            if(response.success === true){
                                supplierTable.DataTable().ajax.reload(null, false);

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


