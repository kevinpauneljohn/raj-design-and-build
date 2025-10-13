@extends('adminlte::page')

@section('title', 'Projects')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>Projects</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">Projects</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-secondary card-outline card-tabs">
        <div class="card-header">
            @can('add project')
                <button class="btn btn-sm btn-secondary" id="add-project-btn">Add</button>
            @endcan
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="project-list" class="table table-striped table-hover border border-2" role="grid" style="width: 100%">
                    <thead>
                    <tr role="row">
                        <th style="width: 15%;">Date Added</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Client</th>
                        <th>Price</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade project-modal" tabindex="-1" role="dialog">
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
                            <div class="form-group col-lg-12 name">
                                <label for="company_name">Name</label><span class="required">*</span>
                                <input type="text" class="form-control" name="name" id="name" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 address">
                                <label for="address">Address</label><span class="required">*</span>
                                <textarea class="form-control" name="address" id=address"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 client_id">
                                <label for="client_id">Client</label>
                                <select name="client_id" class="form-control select2" id="client_id" data-placeholder="Select a client">
                                    <option value="">-- Select a client --</option>
                                    @foreach($clients as $client)
                                        <option value="{{$client->id}}">{{$client->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 price">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" name="price" id="price" step="any"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 description">
                                <label for="description">Description</label> <span class="text-muted"><i>(optional)</i></span>
                                <textarea class="form-control" name="description" id="description" rows="5"></textarea>
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


    <!-- Modal -->
    <div class="modal fade assign-user-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <form class="assign-users-form">
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
                            <div class="form-group col-lg-12 users">
                                <label for="users">Assign Users</label>
                                <select class="form-control select2" name="users[]" id="users" multiple="multiple" style="width: 100%">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                                    @endforeach
                                </select>
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

        $('select[name=client_id]').select2({
            placeholder: 'Select a client',
            allowClear: true,
            dropdownParent: $(".project-modal"),
        });
        $('#users').select2()
        $(function(){

            $('#project-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all-projects') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'address', name: 'address'},
                    { data: 'client', name: 'client'},
                    { data: 'price', name: 'price'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
            });
        });

        let projectModal = $('.project-modal');
        let projectId = '';
        let projectTable = $('#project-list');

        function remove_required_field_errors()
        {
            projectModal.find('.is-invalid').removeClass('is-invalid');
            projectModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            projectModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            projectModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            projectModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            projectModal.find('button[type="submit"]').text('Save');
        }

        @can('add project')
        $(document).on('click','#add-project-btn', function(){
            supplierId = '';
            projectModal.modal('toggle');
            projectModal.find('.modal-title').text('Add Project');
            projectModal.find('form').attr('id','add-project-form');
            projectModal.find('form').trigger('reset');
            projectModal.find('select[name=client_id]').val('').change();
            remove_required_field_errors();
        });

        $(document).on('submit','#add-project-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/project',
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
                    projectTable.DataTable().ajax.reload(null, false);
                    projectModal.find('form').trigger('reset');
                    projectModal.find('select[name=client_id]').val("").change();
                }else{
                    Toast.fire({
                        icon: "danger",
                        title: response.message
                    });
                }

            }).fail(function(xhr, status, error){
                $.each(xhr.responseJSON.errors, function(key, value){
                    projectModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    projectModal.find('#'+key).addClass('is-invalid');
                });

            }).always(function(){
                action_after_form_submission();
            })
        })
        @endcan

        @can('edit project')
        let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

        $(document).on('click','.edit-project-btn', function(){
            projectModal.modal('toggle');
            projectModal.find('.modal-title').text('Edit Project');
            projectModal.find('form').attr('id','edit-project-form');

            projectId = this.id;

            $.ajax({
                url: '/project/'+projectId+'/edit',
                type: 'get',
                beforeSend: function(){
                    projectModal.find('.modal-header').prepend(overlay);
                    remove_required_field_errors();
                }
            }).done(function(response){
                $.each(response, function(key, value){
                    projectModal.find('input[name='+key+'], textarea[name='+key+'], select[name='+key+']').val(value).change();
                });
            }).fail(function(xhr, status, error){
                console.log(xhr);
            }).always(function(){
                projectModal.find('.overlay').remove();
            });
        });


        $(document).on('submit','#edit-project-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/project/'+projectId,
                type: 'put',
                data: data,
                dataType: 'json',
                beforeSend: function(){
                    projectModal.find('.is-invalid').removeClass('is-invalid');
                    projectModal.find('.text-danger').remove();
                    projectModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                    projectModal.find('button[type="submit"]').text('Saving...');
                }
            }).done(function(response){
                console.log(response)
                if(response.success === true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    projectTable.DataTable().ajax.reload(null, false);
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: response.message
                    });
                }
            }).fail(function(xhr, status, error){
                console.log(xhr);

                $.each(xhr.responseJSON.errors, function(key, value){
                    projectModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    projectModal.find('#'+key).addClass('is-invalid');
                });
            }).always(function(){
                projectModal.find('.overlay').remove();
                action_after_form_submission();
            });
        });
        @endcan


        @can('delete project')

        $(document).on('click','.delete-project-btn', function(){
            projectId = this.id;

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
                        'url' : '/project/'+projectId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            if(response.success === true){
                                projectTable.DataTable().ajax.reload(null, false);

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

        @can('assign project to user')
            let assignUserModal = $('.assign-user-modal');
            $(document).on('click','.assign-user-btn', function(){
                projectId = this.id;
                let tr = $(this).closest('tr');

                let data = tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                assignUserModal.modal('toggle');
                assignUserModal.find('.modal-title').text(data[1]);


                $.ajax({
                    url: '/project/'+projectId+'/assigned-users',
                    type: 'get',
                    beforeSend: function(){
                        assignUserModal.find('.modal-header').prepend(overlay);
                    }
                }).done(function(response){
                    assignUserModal.find('#users').val(response).change();
                }).fail(function(xhr, status, error){
                    console.log(xhr);
                }).always(function(){
                    assignUserModal.find('.overlay').remove();
                });
            })

            $(document).on('submit','.assign-users-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '/project/'+projectId+'/assign-user',
                    type: 'post',
                    data: data,
                    beforeSend: function(){
                        assignUserModal.find('.modal-header').prepend(overlay);
                    }
                }).done(function(response){
                    if(response.success === true)
                    {
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                        projectTable.DataTable().ajax.reload(null, false);
                    }
                    else{
                        Toast.fire({
                            icon: "warning",
                            title: response.message
                        });
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr);
                }).always(function(){
                    assignUserModal.find('.overlay').remove();
                });
            });
        @endcan
    </script>
@endpush


