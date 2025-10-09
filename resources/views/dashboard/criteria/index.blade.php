@extends('adminlte::page')

@section('title', 'Criteria')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>Criteria</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">Criteria</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-primary card-outline card-tabs">
        <div class="card-header">
            @if(auth()->user()->can('add criteria'))
                <button class="btn btn-sm btn-primary mb-4" id="add-criteria-btn">Add New</button>
            @endif
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="criteria-list" class="table table-bordered table-hover" role="grid" style="width: 100%">
                    <thead>
                    <tr role="row">
                        <th>Date Added</th>
                        <th style="width: 20%;">Criteria</th>
                        <th style="width: 30%;">Description</th>
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
    <div class="modal fade criteria-modal" id="add-criteria-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <form id="add-criteria-form">
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
                            <div class="form-group col-lg-12 criteria">
                                <label for="criteria">Criteria</label><span class="required">*</span>
                                <input type="text" class="form-control" name="criteria" id="criteria" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea name="description" id="description" class="form-control"></textarea>
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
            $('#criteria-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all-criteria') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'criteria', name: 'criteria'},
                    { data: 'description', name: 'description'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
            });
        });

        let criteriaModal = $('.criteria-modal');
        let criteriaId = '';
        let criteriaTable = $('#criteria-list');

        function remove_required_field_errors()
        {
            criteriaModal.find('.is-invalid').removeClass('is-invalid');
            criteriaModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            criteriaModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            criteriaModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            criteriaModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            criteriaModal.find('button[type="submit"]').text('Save');
        }

        @can('add applicant')
        $(document).on('click','#add-criteria-btn', function(){
            criteriaId = '';
            criteriaModal.modal('toggle');
            criteriaModal.find('.modal-title').text('Add Criteria');
            criteriaModal.find('form').attr('id','add-criteria-form');
            criteriaModal.find('form').trigger('reset');
            remove_required_field_errors();
        });

        $(document).on('submit','#add-criteria-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/criteria',
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
                    criteriaTable.DataTable().ajax.reload(null, false);
                    criteriaModal.find('form').trigger('reset');
                }else{
                    Toast.fire({
                        icon: "danger",
                        title: response.message
                    });
                }

            }).fail(function(xhr, status, error){

                $.each(xhr.responseJSON.errors, function(key, value){
                    criteriaModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    criteriaModal.find('#'+key).addClass('is-invalid');
                });

            }).always(function(){
                action_after_form_submission();
            })
        })
        @endcan

        @can('edit criteria')
        let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

        $(document).on('click','.edit-criteria-btn', function(){
            criteriaModal.modal('toggle');
            criteriaModal.find('.modal-title').text('Edit Criteria');
            criteriaModal.find('form').attr('id','edit-criteria-form');

            criteriaId = this.id;
            console.log(criteriaId)

            $.ajax({
                url: '/criteria/'+criteriaId+'/edit',
                type: 'get',
                beforeSend: function(){
                    criteriaModal.find('.modal-header').prepend(overlay);
                    remove_required_field_errors();
                }
            }).done(function(response){
                console.log(response)
                $.each(response, function(key, value){
                    criteriaModal.find('#'+key).val(value);
                });
            }).fail(function(xhr, status, error){
                console.log(xhr);
            }).always(function(){
                criteriaModal.find('.overlay').remove();
            });
        });


        $(document).on('submit','#edit-criteria-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/criteria/'+criteriaId,
                type: 'put',
                data: data,
                dataType: 'json',
                beforeSend: function(){
                    criteriaModal.find('.is-invalid').removeClass('is-invalid');
                    criteriaModal.find('.text-danger').remove();
                    criteriaModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                    criteriaModal.find('button[type="submit"]').text('Saving...');
                }
            }).done(function(response){
                if(response.success === true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    criteriaTable.DataTable().ajax.reload(null, false);
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: response.message
                    });
                }
            }).fail(function(xhr, status, error){
                console.log(xhr);

                $.each(xhr.responseJSON.errors, function(key, value){
                    criteriaModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    criteriaModal.find('#'+key).addClass('is-invalid');
                });
            }).always(function(){
                criteriaModal.find('.overlay').remove();
                action_after_form_submission();
            });
        });
        @endcan


        @can('delete criteria')

        $(document).on('click','.delete-criteria-btn', function(){
            criteriaId = this.id;

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
                        'url' : '/criteria/'+criteriaId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            if(response.success === true){
                                criteriaTable.DataTable().ajax.reload(null, false);

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


