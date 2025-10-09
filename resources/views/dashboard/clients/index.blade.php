@extends('adminlte::page')

@section('title', 'Clients')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>Clients</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">Clients</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-primary card-outline card-tabs">
        <div class="card-header">
            @if(auth()->user()->can('add client'))
                <button class="btn btn-sm btn-primary mb-4" id="add-client-btn">Add New</button>
            @endif
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="client-list" class="table table-bordered table-hover" role="grid" style="width: 100%">
                    <thead>
                    <tr role="row">
                        <th>Date Added</th>
                        <th style="width: 15%;">Full Name</th>
                        <th>Date Of Birth</th>
                        <th>Mobile Number</th>
                        <th>Email</th>
                        <th style="width: 20%;">Address</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade client-modal" id="add-client-modal" tabindex="-1" role="dialog" aria-labelledby="add-client-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="add-client-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-gray-light">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-4 firstname">
                                <label for="firstname">First Name</label><span class="required">*</span>
                                <input type="text" class="form-control" name="firstname" id="firstname" />
                            </div>
                            <div class="form-group col-lg-4 middlename">
                                <label for="middlename">Middle Name</label>
                                <input type="text" class="form-control" name="middlename" id="middlename" />
                            </div>
                            <div class="form-group col-lg-4 lastname">
                                <label for="lastname">Last Name</label><span class="required">*</span>
                                <input type="text" class="form-control" name="lastname" id="lastname" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 date_of_birth">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" />
                            </div>
                            <div class="form-group col-lg-6 mobile_number">
                                <label for="mobile_number">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile_number" id="mobile_number" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 email">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 address">
                                <label for="username">Address</label><span class="required">*</span>
                                <textarea name="address" id="address" class="form-control"></textarea>
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
            $('#client-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all-clients') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'full_name', name: 'full_name'},
                    { data: 'date_of_birth', name: 'date_of_birth'},
                    { data: 'mobile_number', name: 'mobile_number'},
                    { data: 'email', name: 'email'},
                    { data: 'address', name: 'address'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
            });
        });

        let clientModal = $('.client-modal');
        let clientId = '';
        let clientTable = $('#client-list');

        function remove_required_field_errors()
        {
            clientModal.find('.is-invalid').removeClass('is-invalid');
            clientModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            clientModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            clientModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            clientModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            clientModal.find('button[type="submit"]').text('Save');
        }

        @can('add user')
            $(document).on('click','#add-client-btn', function(){
                clientId = '';
                clientModal.modal('toggle');
                clientModal.find('.modal-title').text('Add Client');
                clientModal.find('form').attr('id','add-client-form');
                clientModal.find('form').trigger('reset');
                remove_required_field_errors();
            });

            $(document).on('submit','#add-client-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '/client',
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
                        clientTable.DataTable().ajax.reload(null, false);
                        clientModal.find('form').trigger('reset');
                    }else{
                        Toast.fire({
                            icon: "danger",
                            title: response.message
                        });
                    }

                }).fail(function(xhr, status, error){

                    $.each(xhr.responseJSON.errors, function(key, value){
                        clientModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                        clientModal.find('#'+key).addClass('is-invalid');
                    });

                }).always(function(){
                    action_after_form_submission();
                })
            })
        @endcan

        @can('edit client')
            let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

            $(document).on('click','.edit-client-btn', function(){
                clientModal.modal('toggle');
                clientModal.find('.modal-title').text('Edit Client');
                clientModal.find('form').attr('id','edit-client-form');

                clientId = this.id;

                $.ajax({
                    url: '/client/'+clientId+'/edit',
                    type: 'get',
                    beforeSend: function(){
                        clientModal.find('.modal-header').prepend(overlay);
                        remove_required_field_errors();
                    }
                }).done(function(response){
                    $.each(response, function(key, value){
                        clientModal.find('#'+key).val(value);
                    });
                }).fail(function(xhr, status, error){
                    console.log(xhr);
                }).always(function(){
                    clientModal.find('.overlay').remove();
                });
            });


            $(document).on('submit','#edit-client-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '/client/'+clientId,
                    type: 'put',
                    data: data,
                    dataType: 'json',
                    beforeSend: function(){
                        clientModal.find('.is-invalid').removeClass('is-invalid');
                        clientModal.find('.text-danger').remove();
                        clientModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                        clientModal.find('button[type="submit"]').text('Saving...');
                    }
                }).done(function(response){
                    if(response.success === true)
                    {
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                        clientTable.DataTable().ajax.reload(null, false);
                    }else{
                        Toast.fire({
                            icon: "warning",
                            title: response.message
                        });
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr);

                    $.each(xhr.responseJSON.errors, function(key, value){
                        clientModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                        clientModal.find('#'+key).addClass('is-invalid');
                    });
                }).always(function(){
                    clientModal.find('.overlay').remove();
                    action_after_form_submission();
                });
            });
        @endcan


        @can('delete client')

        $(document).on('click','.delete-client-btn', function(){
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
                        'url' : '/client/'+clientId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            if(response.success === true){
                                clientTable.DataTable().ajax.reload(null, false);

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


