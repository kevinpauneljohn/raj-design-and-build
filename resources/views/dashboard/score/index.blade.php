@extends('adminlte::page')

@section('title', 'Score')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>Score</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">Score</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-primary card-outline card-tabs">
        <div class="card-header">
            @if(auth()->user()->can('score applicant'))
                <button class="btn btn-sm btn-primary mb-4" id="add-score-btn">Add Score</button>
            @endif
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="score-list" class="table table-bordered table-hover" role="grid" style="width: 100%">
                    <thead>
                    <tr role="row">
                        <th>Date Added</th>
                        <th>Applicant name</th>
                        <th>Address</th>
                        <th>Position</th>
                        <th>Average Score</th>
                        <th>Full Name</th>
{{--                        <th>Action</th>--}}
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade score-modal" id="add-score-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <form id="add-score-form">
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
                            <div class="form-group col-lg-12 applicant_id">
                                <label for="applicant_id">Select Applicant</label><span class="required">*</span>
                                <select class="form-control select2" name="applicant_id" id="applicant_id">
                                    <option value=""> --Select-- </option>
                                    @foreach($applicants as $applicant)
                                        <option value="{{$applicant->id}}">{{ucwords($applicant->name)}} - {{$applicant->mobile_number}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 table-responsive">
                                <div class="mt-3">
                                    <h4>Scoring Guide</h4>
                                    <ul style="list-style-type: none">
                                        <li><span class="text-bold">5</span> = Excellent (Exceeds expectations)</li>
                                        <li><span class="text-bold">4</span> = Very Good (Above average, minor gaps)</li>
                                        <li><span class="text-bold">3</span> = Satisfactory (Meets basic requirements)</li>
                                        <li><span class="text-bold">2</span> = Fair (Somewhat lacking, needs training)</li>
                                        <li><span class="text-bold">1</span> = Poor (Does not meet requirements)</li>
                                    </ul>

                                </div>
                                <table class="table table-bordered table-hover criterion-table">
                                    <thead>
                                        <tr>
                                            <th>Criteria</th>
                                            <th>Description</th>
                                            <th style="width:18%">Score <br/>(1–5)</th>
                                            <th style="width:30%">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($criterion as $criteria)
                                            <tr>
                                                <td class="text-bold">{{$criteria->criteria}}</td>
                                                <td>
                                                    {{$criteria->description}}
                                                    <input name="criteria_id[]" type="hidden" value="{{$criteria->id}}">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="score[]" required>
                                                        <option value=""> --select -- </option>
                                                        <option value="5"> 5 - Excellent </option>
                                                        <option value="4"> 4 - Very Good</option>
                                                        <option value="3"> 3 - Satisfactory</option>
                                                        <option value="2"> 2 - Fair</option>
                                                        <option value="1"> 1 - Poor</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="form-control" name="note[]" maxlength="600" placeholder="(max of 600 characters only)"></textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="applicant_id" value="">
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
            $('#score-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all-scores') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'address', name: 'address'},
                    { data: 'position', name: 'position'},
                    { data: 'average_score', name: 'average_score'},
                    { data: 'panelist', name: 'panelist'},
                    // { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
            });
        });

        let scoreModal = $('.score-modal');
        let scoreId = '';
        let scoreTable = $('#score-list');
        let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

        function remove_required_field_errors()
        {
            scoreModal.find('.is-invalid').removeClass('is-invalid');
            scoreModal.find('.text-danger').remove();
        }

        function action_before_form_submission()
        {
            remove_required_field_errors();
            scoreModal.find('input, textarea, button[type="submit"]').attr('disabled',true);
            scoreModal.find('button[type="submit"]').text('Saving...');
        }

        function action_after_form_submission()
        {
            scoreModal.find('input, textarea, button[type="submit"]').attr('disabled',false);
            scoreModal.find('button[type="submit"]').text('Save');
        }

        @can('score applicant')
        $(document).on('click','#add-score-btn', function(){
            scoreId = '';
            scoreModal.modal('toggle');
            scoreModal.find('.modal-title').text('Add Score');
            scoreModal.find('form').attr('id','add-score-form');
            scoreModal.find('form').trigger('reset');
            remove_required_field_errors();
        });

        $(document).on('change','#applicant_id', function(){
            let applicantId = $(this).val();

            $.ajax({
                url: '/applicant/'+applicantId,
                type: 'get',
                dataType: 'json',
                beforeSend: function(){
                    scoreModal.find('.modal-header').prepend(overlay);
                    $('.applicant-profile').remove();
                    scoreModal.find('input[name=applicant_id]').val(applicantId);
                }
            }).done(function(response){
                scoreModal.find('input[name=applicant_id]').val(response.id);
                let applicantProfile = `
                    <table class="table table-bordered mt-3 applicant-profile">
                        <tr>
                            <td colspan="3">
                                <span class="text-bold">Address</span><br/>
                                <p>${response.address}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="text-bold">Email</span><br/>
                                <p>${response.email}</p>
                            </td>
                            <td>
                                <span class="text-bold">Mobile Number</span><br/>
                                <p>${response.mobile_number}</p>
                            </td>
                            <td>
                                <span class="text-bold">Role Applying</span><br/>
                                <p>${response.position}</p>
                            </td>
                        </tr>
                    </table>
                `;

                scoreModal.find('#applicant_id').after(applicantProfile)
            }).fail(function(){

            }).always(function(){
                scoreModal.find('.overlay').remove();
            });
        });

        $(document).on('submit','#add-score-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            Swal.fire({
                title: `Confirm created score?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: '/score',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        beforeSend: function() {
                            action_before_form_submission();
                        }
                    }).done(function(response){
                        console.log(response)
                        if(response.success === true)
                        {
                            Toast.fire({
                                icon: "success",
                                title: response.message
                            });
                            scoreTable.DataTable().ajax.reload(null, false);
                            scoreModal.find('form').trigger('reset');
                            scoreModal.find('input[name=applicant_id]').val("").change();
                            $('.applicant-profile').remove();
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

                }
            });

        })
        @endcan

        @can('edit criteria')

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


