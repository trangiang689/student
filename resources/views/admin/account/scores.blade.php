@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">{{$student->full_name}}</h1>
@stop

@section('content')

    <div class="alert alert-success alert-dismissible fade show d-none" id="alertEditStudentSuccess">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success!</strong> <span class="contentAlertEditStudent">Update score success</span>
    </div>

    <div class="alert alert-danger alert-dismissible fade show d-none" id="alertEditStudentWarning">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Warning!</strong> <span class="contentAlertEditStudent"></span>
    </div>

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif
    @if(Session::has('warning'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Warning!</strong> {!!Session::get('warning') !!}.
        </div>
    @endif
    <div class="container-fluid ">
        <div class="form-create-faculty" style="max-width: 1000px">
            <div class="form-group">
                {{ Form::label('', 'Email:', ['class' => 'control-label']) }}
                {{ Form::text('', $student->user->email, ['id' => 'name', 'class' => 'form-control', 'disabled' => true]) }}
            </div>

            <div class="form-group">
                {{ Form::label('', 'Faculty name: ', ['class' => 'control-label']) }}
                {{ Form::text('', $student->class->faculty->name, ['id' => 'faculty', 'class' => 'text-capitalize form-control', 'disabled' => true]) }}
            </div>

            <div class="form-group">
                {{ Form::label('', 'Class name: ', ['class' => 'control-label']) }}
                {{ Form::text('', $student->class->name, ['id' => 'class', 'class' => 'form-control', 'disabled' => true]) }}
            </div>

            <div class="form-group" style="max-width: 200px">
                {{ Form::label('', 'Medium score: ', ['class' => 'control-label']) }}
                {{ Form::text('', $student->subjects? $student->subjects->avg('pivot.score') : 'chưa có',
                    ['id' => 'scoresavg', 'class' => 'form-control', 'disabled' => true]) }}
            </div>

            {{----------------------------List Score-------------------------------}}
            {{ Form::label('', 'Transcript :', ['class' => 'control-label mt-5']) }}
            <table class="table table-bordered table-hover table-striped text-center" id="tableScores">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Subject name</th>
                    <th>Score</th>
                    <th>Update at:</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($completedSubjects))
                    @foreach($completedSubjects as $subject)
                        <tr>
                            <td>{{$count++}}</td>
                            <td>{{$subject->name}}</td>
                            <td>{{$subject->pivot->score}}</td>
                            <td>{{$subject->pivot->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

            {{----------------------------subjects not yet learned-------------------------------}}
            {{ Form::label('', 'Subjects not yet learned :', ['class' => 'control-label mt-5']) }}
            <div class="group-input-subject container-fluid row mb-5">
                <div class="col-md-7">
                    @if(!empty(count($unfinishedSubjects)))
                        @foreach($unfinishedSubjects as $unfinishedSubject)
                            <div class="input-group mb-3">
                                <div class="input-group-prepend w-75">
                                    <span
                                        class="input-group-text w-100 mr-3 border-info bg-light text-capitalize">{{$unfinishedSubject->name}}</span>
                                </div>
                                <button data-subject-id="{{$unfinishedSubject->id}}" class="w-25 btn btn-success"
                                        data-toggle="modal" data-target="#modalUpdateScores">Update
                                </button>
                            </div>
                        @endforeach
                    @else
                        <p class="text-secondary">You have completed all specialized subjects</p>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <!-- The Modal -->
    <div class="modal fade" id="modalUpdateScores">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <p class="display-4  modal-title">Update Score</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center">
                    <p class="text-center">Are you sure you want to update score of this subjects?</p>
                    <p class="text-center">The score for this course will be 0</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                            data-subject-id="">Update
                    </button>
                </div>

            </div>
        </div>
    </div>
    {{--    end modal--}}
@stop

@section('css')
@stop
@section('js')
    <script>
        $(".group-input-subject button[data-target='#modalUpdateScores']").on("click", function () {
            $('#modalUpdateScores button[data-subject-id]').attr('data-subject-id', $(this).attr('data-subject-id'));
        });

        /******************************* Update score **********************************/
        $('#modalUpdateScores button[data-subject-id]').on("click", function () {
            var subjectID = $('#modalUpdateScores button[data-subject-id]').attr('data-subject-id');
            var subjectName = $(".group-input-subject button[data-subject-id='" + subjectID + "']").parent().find('span').text();

            var formData = new FormData();
            formData.append('_method', 'put');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            formData.append('subjectid', subjectID);
            $.ajax({
                url: "/admin/scores",
                type: "POST",
                data: {
                    _method: 'put',
                    subjectid: subjectID
                },
                success: function (data) {
                    var result = JSON.parse(data);
                    if (Boolean(result.status)) {
                        $(".group-input-subject button[data-subject-id='" + subjectID + "']").parent().remove();
                        $("#tableScores tbody").append('\n' +
                            '                        <tr>\n' +
                            '                            <td>' + result.data.count + '</td>\n' +
                            '                            <td>' + subjectName + '</td>\n' +
                            '                            <td>0</td>\n' +
                            '                            <td>' + result.data.updated_at + '</td>\n' +
                            '                        </tr>');
                        $('#scoresavg').val(result.data.scoresavg);
                        $('#alertEditStudentSuccess').removeClass('d-none');
                        setTimeout(hideAlertEditStudent, 3000, true);
                    } else {
                        $('#alertEditStudentWarning .contentAlertEditStudent').text(result.msg);
                        $('#alertEditStudentWarning').removeClass('d-none')
                        setTimeout(hideAlertEditStudent, 3000, false);
                    }
                }
            });
        });

        function hideAlertEditStudent(status) {
            if (status) {
                $('#alertEditStudentSuccess').addClass('d-none');
            } else {
                $('#alertEditStudentWarning').addClass('d-none');
            }
        }

    </script>
@stop
