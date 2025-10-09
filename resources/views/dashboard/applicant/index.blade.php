@extends('adminlte::page')

@section('title', 'Applicants')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>Applicants</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">Applicants</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-primary card-outline card-tabs">
        <div class="card-header">
            @if(auth()->user()->can('add applicant'))
                <button class="btn btn-sm btn-primary mb-4" id="add-applicant-btn">Add New</button>
            @endif
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="applicant-list" class="table table-bordered table-hover" role="grid" style="width: 100%">
                    <thead>
                    <tr role="row">
                        <th>Date Added</th>
                        <th style="width: 15%;">Name</th>
                        <th style="width: 20%;">Address</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Position</th>
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
    <div class="modal fade applicant-modal" id="add-applicant-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <form id="add-applicant-form">
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
                            <div class="form-group col-lg-12 name">
                                <label for="name">Name</label><span class="required">*</span>
                                <input type="text" class="form-control" name="name" id="name" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 address">
                                <label for="address">Address</label><span class="required">*</span>
                                <textarea name="address" id="address" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 email">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 mobile_number">
                                <label for="mobile_number">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile_number" id="mobile_number" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 position">
                                <label for="position">Position</label><span class="required">*</span>
                                <select class="form-control" name="position" id="position">
                                    <option value=""> --select-- </option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
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
            $('#applicant-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all-applicant') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'address', name: 'address'},
                    { data: 'email', name: 'email'},
                    { data: 'mobile_number', name: 'mobile_number'},
                    { data: 'position', name: 'position'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
            });
        });

        let applicantModal = $('.applicant-modal');
        let applicantId = '';
        let applicantTable = $('#applicant-list');

        function remove_required_field_errors()
        {
            applicantModal.find('.is-invalid').removeClass('is-invalid');
            applicantModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            applicantModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            applicantModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            applicantModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            applicantModal.find('button[type="submit"]').text('Save');
        }

        @can('add applicant')
        $(document).on('click','#add-applicant-btn', function(){
            applicantId = '';
            applicantModal.modal('toggle');
            applicantModal.find('.modal-title').text('Add Applicant');
            applicantModal.find('form').attr('id','add-applicant-form');
            applicantModal.find('form').trigger('reset');
            remove_required_field_errors();
        });

        $(document).on('submit','#add-applicant-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/applicant',
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
                    applicantTable.DataTable().ajax.reload(null, false);
                    applicantModal.find('form').trigger('reset');
                }else{
                    Toast.fire({
                        icon: "danger",
                        title: response.message
                    });
                }

            }).fail(function(xhr, status, error){

                $.each(xhr.responseJSON.errors, function(key, value){
                    applicantModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    applicantModal.find('#'+key).addClass('is-invalid');
                });

            }).always(function(){
                action_after_form_submission();
            })
        })
        @endcan

        @can('edit applicant')
        let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

        $(document).on('click','.edit-applicant-btn', function(){
            applicantModal.modal('toggle');
            applicantModal.find('.modal-title').text('Edit Applicant');
            applicantModal.find('form').attr('id','edit-applicant-form');

            applicantId = this.id;

            $.ajax({
                url: '/applicant/'+applicantId+'/edit',
                type: 'get',
                beforeSend: function(){
                    applicantModal.find('.modal-header').prepend(overlay);
                    remove_required_field_errors();
                }
            }).done(function(response){
                $.each(response, function(key, value){
                    applicantModal.find('#'+key).val(value);
                });
            }).fail(function(xhr, status, error){
                console.log(xhr);
            }).always(function(){
                applicantModal.find('.overlay').remove();
            });
        });


        $(document).on('submit','#edit-applicant-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/applicant/'+applicantId,
                type: 'put',
                data: data,
                dataType: 'json',
                beforeSend: function(){
                    applicantModal.find('.is-invalid').removeClass('is-invalid');
                    applicantModal.find('.text-danger').remove();
                    applicantModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                    applicantModal.find('button[type="submit"]').text('Saving...');
                }
            }).done(function(response){
                if(response.success === true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    applicantTable.DataTable().ajax.reload(null, false);
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: response.message
                    });
                }
            }).fail(function(xhr, status, error){
                console.log(xhr);

                $.each(xhr.responseJSON.errors, function(key, value){
                    applicantModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    applicantModal.find('#'+key).addClass('is-invalid');
                });
            }).always(function(){
                applicantModal.find('.overlay').remove();
                action_after_form_submission();
            });
        });
        @endcan


        @can('delete applicant')

        $(document).on('click','.delete-applicant-btn', function(){
            applicantId = this.id;

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
                        'url' : '/applicant/'+applicantId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            if(response.success === true){
                                applicantTable.DataTable().ajax.reload(null, false);

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


