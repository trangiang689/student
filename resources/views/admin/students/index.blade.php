@extends('adminlte::page')
@section('title', 'faculties/add faculty')

@section('content_header')
    <h1 class="title-header">Faculty <span class="small font-weight-normal">List</span></h1>
@stop
@section('content')
    <div class="alert alert-success alert-dismissible fade show d-none" id="alertEditStudentSuccess">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success!</strong> <span class="contentAlertEditStudent">Update student Success</span>
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

    {{--    START FILTER--}}
    <div class="form-group d-inline p-2 m-0">
        <p class="float-left">Show </p>

        {{Form::select('checkBoxShow', \Config::get('constants.PER_PAGES') , $limit, ['placeholder' => 'Pick one', 'class' =>'float-left mx-2', 'id'=> 'checkBoxShow', 'style' => 'min-width: 75px;' ])}}
        <p class="float-left mr-5">entries</p>
    </div>

    {{--    filter phone number--}}
    <div class="form-group d-inline p-2 m-0">
        <p class="float-left">Phone number: </p>
        <?php
        foreach (array_keys(\Config::get('constants.PHONE_NUMBERS')) as $phoneNumberType) {
            $phoneNumberTypes[$phoneNumberType] = $phoneNumberType;
        }
        ?>
        {{Form::select('phoneNumberType', $phoneNumberTypes, $request->get('phonenumbertype'), [ 'placeholder' => 'all', 'class' =>'text-capitalize float-left mx-2 mr-5', 'style' => 'min-width: 150px;', 'id' => 'phoneNumberType' ])}}
    </div>
    {{--   End filter phone number--}}

    {{--    filter year old--}}
    <div class="float-left">
        <p class="float-left ">Year old :</p>
        {{Form::text('yearOldStart',$request->get('yearoldstart'), ['class' => 'p-0 float-left mx-sm-2', 'placeholder' => 'From', 'style' => 'width: 70px;', 'id' =>'yearOldStart'])}}
        {{Form::text('yearOldEnd',$request->get('yearoldend'), ['class' => 'p-0 float-left', 'placeholder' => 'to', 'style' => 'width: 70px;', 'id' =>'yearOldEnd'])}}
    </div>
    {{--end filter year old--}}


    {{--    filter score--}}
    <div class="float-left">
        <p class="float-left ml-5">Score :</p>
        {{Form::text('scoreStart',$request->get('scorestart'), ['class' => 'p-0 float-left mx-sm-2', 'placeholder' => 'From', 'style' => 'width: 70px;', 'id' =>'scoreStart'])}}
        {{Form::text('scoreEnd',$request->get('scoreend'), ['class' => 'p-0 float-left', 'placeholder' => 'to', 'style' => 'width: 70px;', 'id' =>'scoreEnd'])}}
    </div>
    {{--    end filter year old--}}


    {{--    filter completed the course--}}
    <div class="float-left">
        <p class="float-left ml-5">Completed the course : </p>
        {{Form::select('completedCourse', ['all' => 'all', 'yes' =>'yes', 'no'=> 'no'], $request->get('completedcourse'),
                ['class' =>'float-left mx-2 text-capitalize', 'style' => 'min-width: 150px;', 'id' => 'completedCourse' ])}}
    </div>
    {{--    End filter completed the course--}}

    <button class="btn py-0 bg-dark btnSubmitFilter" type="button">Filter</button>
    {{--    END FILTER--}}
    <table class="table table-bordered text-center table-hover">
        <thead class="">
        <tr>
            <th>STT</th>
            <th>Student name</th>
            <th class="">Email</th>
            <th>Phone number</th>
            <th>Faculty name</th>
            <th>Medium score</th>
            <th>Birth date</th>
            <th>Home town</th>
            <th>Avatar</th>
            <th>View</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody id="myTable">
        @foreach($students as $student)
            <tr>
                <td>{{$count++}}</td>
                <td class="tdFullName">{{$student->full_name}}</td>
                <td class="tdEmail">{{$student->user->email}}</td>
                <td class="tdPhoneNumber">{{$student->phone_number}}</td>
                <td class="tdFacultyName" class="text-capitalize">{{$student->class->faculty->name}}</td>
                <td class="tdScoreAVG">{{$student->subjects->avg('pivot.score') ?? 'chưa có'}}</td>
                <td class="tdBirthDate">{{date_format(date_create($student->birth_date),"d/m/Y")}}</td>
                <td class="tdHomeTown">{{$student->home_town}}</td>
                <td class="tdAvatar" style="width: 250px; height: 250px;">
                    <img
                        src="/{{\Config::get('constants.LOCATION_AVATARS')}}/{{empty($student->avatar)? \Config::get('constants.AVATAR_DEFAULT_NAME') : $student->avatar}}"
                        alt="avatar"
                        class="bg-light img-fluid" style="max-height: 250px;"
                    >
                </td>
                <td><a class="text-reset" href="{{route('admin.students.show', ['student' => $student->id])}}"><i
                            class="fas fa-eye"></i></a></td>

                <td>
                    <a class="btn-update" data-toggle="modal" data-target="#modalUpdate"
                       data-student-id="{{$student->id}}" type="button">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>

                <td>
                    <a class="btn-destroy" data-toggle="modal" data-target="#modalComfirmDelete"
                       data-url="{{route('admin.students.destroy',['student' =>$student->id])}}" type="button">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{--    modal--}}
    <!-- The Modal Delete -->
    <div class="modal fade" id="modalComfirmDelete">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <p class="display-4  modal-title">Delete Faculty</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center">
                    <p class="text-center">Are you sure you want to delete this class?</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    {{Form::open(['method' => 'DELETE', 'url' => ' ', 'id' => 'form-destroy' ])}}
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    {{ Form::submit('Delete', ['class' => 'btn btn-success'])}}
                    {{Form::close()}}
                </div>

            </div>
        </div>
    </div>

    <!-- The Modal Edit -->
    <div class="modal fade" id="modalUpdate">
        <div class="modal-dialog" style="max-width: 900px;">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <p class="modal-title display-4" style="font-size: 2.5rem;">Edit Student</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body justify-content-center">

                    <div class="iconWaiLoadData spinner-border mx-auto text-primary mx-auto d-none"></div>

                    <div id="msgEditStudent" class="d-none">

                    </div>
                    <div id="boxEditStudent" class="">
                        <div class="form-group">
                            <label for="full_name" class="control-label">Full name</label>
                            <input class="form-control" name="full_name" type="text" value="Quách hà" id="full_name">
                        </div>

                        <div class="form-group">
                            <label for="email" class="control-label">Email:</label>
                            <input class="form-control" name="email" type="text" value="sangnt72@wru.vn" id="email">
                        </div>

                        <div class="form-group">
                            <label for="phone_number" class="control-label">Phone number:</label>
                            <input class="form-control" name="phone_number" type="text" value="0916822261"
                                   id="phone_number">
                        </div>

                        <div class="form-group">
                            <label for="birth_date" class="control-label">Birth day:</label>
                            <input class="form-control" name="birth_date" type="date" value="1999-11-30"
                                   id="birth_date">
                        </div>

                        <div class="form-group">
                            <label for="home_town" class="control-label">Address:</label>
                            <input class="form-control" name="home_town" type="text" value="Lâm Đồng" id="home_town">
                        </div>

                        <div class="form-group w-50">
                            <label for="faculty_id" class="control-label">Select faculty:</label>
                            {{Form::select('faculty_id', $faculties, '',
                                ['placeholder' => 'Pick a faculty', 'class' =>'text-capitalize custom-select', 'id' => 'faculty_id'])}}
                        </div>

                        <div class="form-group w-50">
                            <label for="class_id" class="control-label">Select class:</label>
                            <select class="custom-select" id="class_id" name="class_id">
                                <option value>Pick a class</option>
                                <div class="optionsOfClasses">
                                    @foreach($classes as $key => $value)
                                        @foreach($value as $class)
                                            <option class="d-none" data-parentFaculty="{{$class->faculty_id}}"
                                                    value="{{$class->id}}">{{$class->name}}</option>
                                        @endforeach
                                    @endforeach
                                </div>
                            </select>
                        </div>

                        <label for="" class="control-label w-100">Avatar:</label>
                        <div class="custom-file mb-3 w-50">
                            <input type="file" class="custom-file-input" id="avatar" name="avatar"
                                   onchange="readURL(this);">
                            <label class="custom-file-label" for="avatar">Choose file</label>
                        </div>

                        <div class="mt-3 w-100">
                            <div style="width: 250px; height: 250px; background-color: #eee " class="text-center">
                                <img id="imgAvatar"
                                     src="/images/avatar/avatar_default.png"
                                     alt="avatar"
                                     class="bg-light img-fluid" style="max-height: 250px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btnSubmitUpdateStudent" data-dismiss="modal"
                            data-student-id="">Save
                    </button>
                </div>

            </div>
        </div>
    </div>
    {{--    end modal--}}
    <ul class="pagination justify-content-center ">
        <li class="page-item"><a class="page-link" href="{{$students->appends($request->all())->url(1)}}"><i
                    class="fas fa-angle-double-left"></i></a></li>
        @if($students->currentPage() > 1)
            <li class="page-item"><a class="page-link"
                                     href="{{$students->appends($request->all())->previousPageUrl()}}">{{$students->currentPage() - 1}}</a>
            </li>
        @endif
        <li class="page-item"><a class="page-link">{{$students->currentPage()}}</a></li>
        @if($students->hasMorePages())
            <li class="page-item"><a class="page-link"
                                     href="{{$students->appends($request->all())->nextPageUrl()}}">{{$students->currentPage() + 1}}</a>
            </li>
        @endif
        <li class="page-item"><a class="page-link"
                                 href="{{$students->appends($request->all())->url($students->lastPage())}}"><i
                    class="fas fa-angle-double-right"></i></a></li>
    </ul>
@stop

@section('css')
    {{--CSS--}}
@stop
@section('js')
    <script>
        $(document).ready(function () {
            $(".btn-destroy").on("click", function () {
                $('#form-destroy').attr('action', $(this).attr('data-url'));
            });
            //edit student
            $(".btn-update").on("click", function () {
                getFormEditStudent($(this).attr('data-student-id'));
            });


            $('.btnSubmitFilter').on('click', function () {
                var url = "?page=1";
                var limit = $('#checkBoxShow').val();
                var phoneNumberType = $('#phoneNumberType').val();
                var yearOldStar = $('#yearOldStart').val();
                var yearOldEnd = $('#yearOldEnd').val();
                var scoreStart = $('#scoreStart').val();
                var scoreEnd = $('#scoreEnd').val();
                var completedCourse = $('#completedCourse').val();
                if(limit != ""){
                    url = url + "&limit=" + limit;
                }
                if(phoneNumberType != ""){
                    url = url + "&phonenumbertype=" + phoneNumberType;
                }
                if(yearOldStar != ""){
                    url = url + "&yearoldstart=" + yearOldStar;
                }
                if(yearOldEnd != ""){
                    url = url + "&yearoldend=" + yearOldEnd;
                }
                if(scoreStart != ""){
                    url = url + "&scorestart=" + scoreStart;
                }
                if(scoreEnd != ""){
                    url = url + "&scoreend=" + scoreEnd;
                }
                if(completedCourse != "all"){
                    url = url + "&completedcourse=" + completedCourse;
                }
                window.location = url;
                // window.location = "?limit=" + limit + "&page=1&phonenumbertype=" + phoneNumberType + "&yearoldstart=" + yearOldStar + "&yearoldend=" + yearOldEnd
                //     + "&scorestart=" + scoreStart + "&scoreend=" + scoreEnd + "&completedcourse=" + completedCourse;
            });

            //load avatar
            $(".custom-file-input").on("change", function () {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });

            //load select class

            $("select[name = faculty_id]").on('change', function () {
                var facultySelected = $(this).val();
                $('select[name = class_id] option').removeClass('d-none');
                $('select[name = class_id] option[data-parentFaculty]').addClass('d-none');
                $('select[name = class_id] option[data-parentFaculty =' + facultySelected + ']').removeClass('d-none');
            });


            // Submit updates student
            $(".btnSubmitUpdateStudent").on('click', function () {
                updateStudent($(this).attr('data-student-id'));
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#imgAvatar')
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function getFormEditStudent(studentId) {
            $('#modalUpdate .modal-body #msgEditStudent').addClass('d-none');
            $('#modalUpdate .modal-body .iconWaiLoadData').removeClass('d-none');
            $('#modalUpdate .modal-body .iconWaiLoadData').addClass('d-block');
            $('#modalUpdate .modal-body #boxEditStudent').addClass('d-none');
            $.ajax({
                url: "/api/students/" + studentId,
                success: function (data) {
                    var student = JSON.parse(data);
                    if (Boolean(student.status)) {
                        $('#modalUpdate .modal-body .iconWaiLoadData').removeClass('d-block');
                        $('#modalUpdate .modal-body .iconWaiLoadData').addClass('d-none');
                        $('#modalUpdate .modal-body #boxEditStudent').removeClass('d-none');
                        $('#modalUpdate .modal-body #full_name').val(student.data.full_name);
                        $('#modalUpdate .modal-body #email').val(student.data.user.email);
                        $('#modalUpdate .modal-body #phone_number').val(student.data.phone_number);
                        $('#modalUpdate .modal-body #birth_date').val(student.data.birth_date);
                        $('#modalUpdate .modal-body #home_town').val(student.data.home_town);
                        $('#modalUpdate .modal-body #faculty_id').val(student.data.class.faculty_id);
                        $('#modalUpdate .modal-body #avatar').val('');
                        $('#modalUpdate .modal-body .custom-file-label').removeClass("selected").html("");

                        $('#modalUpdate .modal-body #class_id').val(student.data.class_id);
                        if(student.data.avatar ==""){
                            $('#modalUpdate .modal-body #imgAvatar').attr('src', '/images/avatar/avatar_default.png');
                        }else{
                            $('#modalUpdate .modal-body #imgAvatar').attr('src', '/images/avatar/' + student.data.avatar);
                        }


                        $('select[name = class_id] option').removeClass('d-none');
                        $('select[name = class_id] option[data-parentFaculty]').addClass('d-none');
                        $('select[name = class_id] option[data-parentFaculty =' + student.data.class.faculty_id + ']').removeClass('d-none');

                    } else {
                        $('#modalUpdate .modal-body .iconWaiLoadData').removeClass('d-block');
                        $('#modalUpdate .modal-body .iconWaiLoadData').addClass('d-none');
                        $('#modalUpdate .modal-body #msgEditStudent').removeClass('d-none');
                        $('#modalUpdate .modal-body #msgEditStudent').html(student.msg);
                    }
                }
            });
            $('#modalUpdate .btnSubmitUpdateStudent').attr('data-student-id', studentId);
        }

        function updateStudent(studentId) {
            var formData = new FormData();
            formData.append('_method', 'put');
            formData.append('full_name', $('#checkipname').val());
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var name = $('#modalUpdate .modal-body #full_name').val();
            var full_name = $('#modalUpdate .modal-body #full_name').val();
            var email = $('#modalUpdate .modal-body #email').val();
            var phone_number = $('#modalUpdate .modal-body #phone_number').val();
            var birth_date = $('#modalUpdate .modal-body #birth_date').val();
            var home_town = $('#modalUpdate .modal-body #home_town').val();
            var class_id = $('#modalUpdate .modal-body #class_id').val();
            var avatar = $('#modalUpdate .modal-body #avatar').prop('files');
            if ($('#modalUpdate .modal-body #avatar').prop('files').length > 0) {
                formData.append('avatar', avatar[0]);
            }
            formData.append('name', name);
            formData.append('full_name', full_name);
            formData.append('email', email);
            formData.append('phone_number', phone_number);
            formData.append('birth_date', birth_date);
            formData.append('home_town', home_town);
            formData.append('class_id', class_id);
            $.ajax({
                url: "/api/students/" + studentId,
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {
                    console.log(data);
                    var result = JSON.parse(data);
                    if (Boolean(result.status)) {
                        reLoadDataStudentEdit(result.data.id, result.data);
                        $('#alertEditStudentSuccess').removeClass('d-none');
                        setTimeout(hideAlertEditStudent, 3000, true);
                    } else {
                        $('#alertEditStudentWarning .contentAlertEditStudent').text(result.msg);
                        $('#alertEditStudentWarning').removeClass('d-none')
                        setTimeout(hideAlertEditStudent, 3000, false);
                    }
                }
            });
        }

        function hideAlertEditStudent(status) {
            if (status) {
                $('#alertEditStudentSuccess').addClass('d-none');
            } else {
                $('#alertEditStudentWarning').addClass('d-none');
            }
        }

        function reLoadDataStudentEdit(student_id, data) {
            var elementShowData = $('.btn-update[data-student-id=' + student_id + ']').parent().parent();

            elementShowData.find('.tdFullName').text(data.full_name);
            elementShowData.find('.tdEmail').text(data.user.email);
            elementShowData.find('.tdPhoneNumber').text(data.phone_number);
            elementShowData.find('.tdFacultyName').text(data.class.faculty.name);
            elementShowData.find('.tdBirthDate').text(data.birth_date);
            elementShowData.find('.tdHomeTown').text(data.home_town);
            // elementShowData.find('.tdScoreAVG').text();

            if ($('#modalUpdate .modal-body #avatar').prop('files') && $('#modalUpdate .modal-body #avatar').prop('files')[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    elementShowData.find('.tdAvatar img').attr('src', e.target.result);
                };
                reader.readAsDataURL($('#modalUpdate .modal-body #avatar').prop('files')[0]);
            }
        }
    </script>
@stop
