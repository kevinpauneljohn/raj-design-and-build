@extends('adminlte::page')

@section('title', 'Projects Phases')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>Project Phases</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item"><a href="{{route('project.index')}}">Projects</a> </li>
                <li class="breadcrumb-item"><a href="{{route('project.show',['project' => $project->id])}}">Project Details</a> </li>
                <li class="breadcrumb-item active">Project Phase</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card card-secondary card-outline">
                <div class="card-body">
                    <strong><i class="fas fa-ticket-alt"></i> ID </strong><p class="text-mute text-gray">{{$project->id}}</p>
                    <hr>

                    <strong><i class="fa fa-check-square mr-1"></i> Name</strong>

                    <p class="text-muted text-blue">
                        {{ucwords($project->name)}}
                    </p>

                    <hr>

                    <strong><i class="fas fa-calendar-check mr-1"></i> Date Created</strong>

                    <p class="text-muted">
                        {{$project->created_at->format('M-d-Y h:i A')}}
                    </p>
                    <hr>
                    <strong><i class="fas fa-user mr-1"></i> Client Name</strong>

                    <p class="text-muted">
                        {{$project->client->full_name}}
                    </p>

                    <div id="request-status">
                        <hr>
                        <strong><i class="fa fa-tags mr-1"></i> Status</strong>

                        <p class="text-muted">
                            <select name="status" class="form-control">
                                <option value="pending" selected="">Pending</option>
                                <option value="on-going">On-going</option>
                                <option value="delivered">Delivered</option>
                                <option value="completed">Completed</option>
                                <option value="declined">Declined</option>
                            </select>

                        </p>
                    </div>
                </div>
            </div>
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Project Description</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <p class="text-muted">
                        @if(is_null($project->description))
                            No description found
                        @else
                            {!! nl2br($project->description) !!}
                        @endif

                    </p>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    @can('add phase')
                        <button class="btn btn-secondary btn-xs" id="add-phase-btn">Add Phase</button>
                    @endcan
                </div>
                <div class="card-body">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <table id="phase-list" class="table table-striped table-hover border border-2" role="grid" style="width: 100%">
                            <thead>
                            <tr role="row">
                                <th style="width: 15%;">Date Created</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Percentage</th>
                                <th>Timeline</th>
                                <th>Category</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade phase-modal" tabindex="-1" role="dialog">
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
                                <label for="name">Name</label><span class="required">*</span>
                                <input type="text" class="form-control" name="name" id="name" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 description">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id=description"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 percentage">
                                <label for="percentage">Percentage</label><span class="required">*</span>
                                <input type="number" class="form-control" name="percentage" id="percentage" step="any" placeholder="{{$remaining_percentage}}%" min="0"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label for="sales-dates">Set Timeline</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                  </span>
                                    </div>
                                    <input type="text" name="timeline" class="form-control float-right" id="timeline" autocomplete="off">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 category">
                                <label for="category">Category</label><span class="required">*</span>
                                <select class="form-control" name="category" id="category">
                                    <option value=""> --Select category-- </option>
                                    <option value="pre-construction">Pre-construction</option>
                                    <option value="construction">Construction</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="project_id" value="{{$project->id}}">
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
@section('plugins.Moment',true)
@section('plugins.DateRangePicker',true)

@section('plugins.tempusdominusBootstrap4',true)
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
            $('#timeline').daterangepicker()
            $('#phase-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all-phases',['project' => $project->id]) !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'description', name: 'description'},
                    { data: 'percentage', name: 'percentage'},
                    { data: 'timeline', name: 'timeline'},
                    { data: 'category', name: 'category'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
                drawCallback: function(row){
                    let request = row.json;

                    $('#phase-list').find('tbody')
                        .append('<tr><td colspan="3"><span class="text-bold"></td><td colspan="4">Total: <span class="text-bold text-danger">'+request.total_percentage+'%</span></td></tr>')
                }
            });
        });

        let phaseModal = $('.phase-modal');
        let phaseId = '';
        let phaseTable = $('#phase-list');

        function remove_required_field_errors()
        {
            phaseModal.find('.is-invalid').removeClass('is-invalid');
            phaseModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            phaseModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            phaseModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            phaseModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            phaseModal.find('button[type="submit"]').text('Save');
        }

        @can('add phase')
        $(document).on('click','#add-phase-btn', function(){
            phaseId = '';
            phaseModal.modal('toggle');
            phaseModal.find('.modal-title').text('Add Phase');
            phaseModal.find('form').attr('id','add-phase-form');
            phaseModal.find('form').trigger('reset');
            remove_required_field_errors();
        });

        $(document).on('submit','#add-phase-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/phase',
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    action_before_form_submission();
                }
            }).done(function(response){
                console.log(response);
                if(response.success === true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });

                    phaseModal.find('#percentage').attr({
                        'placeholder':response.remaining_percentage+'%',
                        'max' : response.maremaining_percentage
                    });
                    phaseTable.DataTable().ajax.reload(null, false);
                    phaseModal.find('form').trigger('reset');
                }else{
                    Toast.fire({
                        icon: "danger",
                        title: response.message
                    });
                }

            }).fail(function(xhr, status, error){
                $.each(xhr.responseJSON.errors, function(key, value){
                    phaseModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    phaseModal.find('#'+key).addClass('is-invalid');
                });

            }).always(function(){
                action_after_form_submission();
            })
        })
        @endcan

        @can('edit phase')
        let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

        $(document).on('click','.edit-phase-btn', function(){
            phaseModal.modal('toggle');
            phaseModal.find('.modal-title').text('Edit Phase');
            phaseModal.find('form').attr('id','edit-phase-form');

            phaseId = this.id;

            $.ajax({
                url: '/phase/'+phaseId+'/edit',
                type: 'get',
                beforeSend: function(){
                    phaseModal.find('.modal-header').prepend(overlay);
                    remove_required_field_errors();
                }
            }).done(function(response){
                console.log(response);
                if(response.start_date === null || response.end_date === null)
                {
                    $('#timeline').val('').change();
                }else{
                    $('#timeline').daterangepicker({
                        startDate: moment(response.start_date).format('MM-DD-Y'),
                        endDate: moment(response.end_date).format('MM-DD-Y'),
                    });
                }

                $.each(response, function(key, value){
                    phaseModal.find('input[name='+key+'], textarea[name='+key+'], select[name='+key+']').val(value).change();
                });
            }).fail(function(xhr, status, error){
                console.log(xhr);
            }).always(function(){
                phaseModal.find('.overlay').remove();
            });
        });


        $(document).on('submit','#edit-phase-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            $.ajax({
                url: '/phase/'+phaseId,
                type: 'put',
                data: data,
                dataType: 'json',
                beforeSend: function(){
                    phaseModal.find('.is-invalid').removeClass('is-invalid');
                    phaseModal.find('.text-danger').remove();
                    phaseModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
                    phaseModal.find('button[type="submit"]').text('Saving...');
                }
            }).done(function(response){
                console.log(response)
                if(response.success === true)
                {
                    phaseModal.find('#percentage').attr({
                        'placeholder':response.remaining_percentage+'%',
                        'max' : response.maremaining_percentage
                    });
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    phaseTable.DataTable().ajax.reload(null, false);
                }else{
                    Toast.fire({
                        icon: "warning",
                        title: response.message
                    });
                }
            }).fail(function(xhr, status, error){
                console.log(xhr);

                $.each(xhr.responseJSON.errors, function(key, value){
                    phaseModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    phaseModal.find('#'+key).addClass('is-invalid');
                });
            }).always(function(){
                phaseModal.find('.overlay').remove();
                action_after_form_submission();
            });
        });
        @endcan


        @can('delete phase')

        $(document).on('click','.delete-phase-btn', function(){
            phaseId = this.id;

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
                        'url' : '/phase/'+phaseId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){
                            if(response.success === true){
                                phaseTable.DataTable().ajax.reload(null, false);

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


