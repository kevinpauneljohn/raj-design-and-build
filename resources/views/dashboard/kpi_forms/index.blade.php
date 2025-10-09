@extends('adminlte::page')

@section('title', 'KPI Forms')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>KPI Forms</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">KPI Forms</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-primary card-outline card-tabs">
        <div class="card-header">
            @if(auth()->user()->can('add kpi'))
                <button class="btn btn-sm btn-primary mb-4" id="add-kpi-form-btn">Add</button>
            @endif
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="kpi-form-list" class="table table-bordered table-hover" role="grid" style="width: 100%">
                    <thead>
                    <tr role="row">
                        <th>Date Created</th>
                        <th>Form Name</th>
                        <th>Description</th>
                        <th>Assessment From</th>
                        <th>Assessment To</th>
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
    <div class="modal fade kpi-form-modal" id="add-kpi-form-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <form id="add-kpi-form-form">
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
                            <div class="col-lg-12 name">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12 description">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12 assessment_from">
                                <label for="assessment_from">Assessment From</label>
                                <select name="assessment_from[]" class="form-control select2" id="assessment_from" multiple="multiple" style="width: 100%">
                                    @foreach($roles as $key => $role)
                                        <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12 assessment_to">
                                <label for="assessment_to">Assessment To</label>
                                <select name="assessment_to" class="form-control select2" id="assessment_to" style="width: 100%">
                                    <option value=""> -- Select -- </option>
                                    @foreach($roles as $key => $role)
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
            $('#assessment_from').select2()
            $('#kpi-form-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all-kpi-forms') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'description', name: 'description'},
                    { data: 'assessment_from', name: 'assessment_from'},
                    { data: 'assessment_to', name: 'assessment_to'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
            });
        });

        let kpiFormModal = $('.kpi-form-modal');
        let kpiFormId = '';
        let kpiFormTable = $('#kpi-form-list');
        let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

        function remove_required_field_errors()
        {
            kpiFormModal.find('.is-invalid').removeClass('is-invalid');
            kpiFormModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            kpiFormModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            kpiFormModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            kpiFormModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            kpiFormModal.find('button[type="submit"]').text('Save');
        }

        @can('add kpi')
        $(document).on('click','#add-kpi-form-btn', function(){
            kpiFormId = '';
            kpiFormModal.modal('toggle');
            kpiFormModal.find('.modal-title').text('Add KPI Form');
            kpiFormModal.find('form').attr('id','add-kpi-form-form');
            kpiFormModal.find('form').trigger('reset');
            remove_required_field_errors();
        });


        $(document).on('submit','#add-kpi-form-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/kpi-forms',
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
                    kpiFormTable.DataTable().ajax.reload(null, false);
                    kpiFormModal.find('form').trigger('reset');
                    kpiFormModal.find('.select2').val(null).change();
                }else{
                    Toast.fire({
                        icon: "danger",
                        title: response.message
                    });
                }

            }).fail(function(xhr, status, error){

                $.each(xhr.responseJSON.errors, function(key, value){
                    kpiFormModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    kpiFormModal.find('#'+key).addClass('is-invalid');
                });

            }).always(function(){
                action_after_form_submission();
            })

        })
        @endcan

        @can('edit kpi')

        $(document).on('click','.edit-kpi-form-btn', function(){
            kpiFormModal.modal('toggle');
            kpiFormModal.find('.modal-title').text('Edit KPI Form');
            kpiFormModal.find('form').attr('id','edit-kpi-form-form');

            kpiFormId = this.id;
            console.log(kpiFormId)

            $.ajax({
                url: '/kpi-forms/'+kpiFormId+'/edit',
                type: 'get',
                beforeSend: function(){
                    kpiFormModal.find('.modal-header').prepend(overlay);
                    remove_required_field_errors();
                }
            }).done(function(response){
                $.each(response, function(key, value){
                    kpiFormModal.find('#'+key).val(value);
                });
                kpiFormModal.find('#assessment_from').val(response.assessment_from).change();
            }).fail(function(xhr, status, error){
                console.log(xhr);
            }).always(function(){
                kpiFormModal.find('.overlay').remove();
            });
        });


        $(document).on('submit','#edit-kpi-form-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/kpi-forms/'+kpiFormId,
                type: 'put',
                data: data,
                dataType: 'json',
                beforeSend: function(){
                    kpiFormModal.find('.is-invalid').removeClass('is-invalid');
                    kpiFormModal.find('.text-danger').remove();
                    kpiFormModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                    kpiFormModal.find('button[type="submit"]').text('Saving...');
                }
            }).done(function(response){
                if(response.success === true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    kpiFormTable.DataTable().ajax.reload(null, false);
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: response.message
                    });
                }
            }).fail(function(xhr, status, error){
                console.log(xhr);

                $.each(xhr.responseJSON.errors, function(key, value){
                    kpiFormModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    kpiFormModal.find('#'+key).addClass('is-invalid');
                });
            }).always(function(){
                kpiFormModal.find('.overlay').remove();
                action_after_form_submission();
            });
        });
        @endcan


        @can('delete kpi')

        $(document).on('click','.delete-kpi-form-btn', function(){
            kpiFormId = this.id;

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
                        'url' : '/kpi-forms/'+kpiFormId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            if(response.success === true){
                                kpiFormTable.DataTable().ajax.reload(null, false);

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


