var oldSubject = '';
$(document).ready(function () {
    $(".add-input-subject").on("click", function ($this) {
        var dataAdd = $('#dataInputAdd').html();
        $('.group-input-subject > div').append(dataAdd);
        $('.group-input-subject .input-subject[name= ""]').attr('name', 'subjects[]');
        $('.group-input-subject .input-score[name= ""]').attr('name', 'scores[]');
        disableSubjects();
    });

    $(".btn-resetForm").on("click", function () {
        resetForm();
    });

    disableSubjects();

    $("#formEditStudent").submit(function () {
        resetOptionDisableToSubmit(this);
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
});
reloadPageCreateSubjects();
disableSubjects();


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

function reloadPageCreateSubjects() {
    var dataOldSubjects = $('input[name=scoresInfo]').attr('data-oldSubjects');
    dataOldSubjects = JSON.parse(dataOldSubjects)
    var subjects = $('input[name=scoresInfo]').attr('data-disabled');
    subjects = JSON.parse(subjects);

    dataOldSubjects.forEach(function(value){
        if (value != null) {
            subjects.push(Number(value));
        }
    });
    subjects = JSON.stringify(subjects);
    $('input[name=scoresInfo]').attr('data-disabled', subjects);

    disableSubjects();
}

function changeChoseSubject($this) {
    var subject = $this.value;
    if (subject == "") {
        if (oldSubject == "") {
        } else {
            var subjects = $('input[name=scoresInfo]').attr('data-disabled');
            subjects = JSON.parse(subjects)
            for (let i = 0; i < subjects.length; i++) {
                if (subjects[i] == oldSubject) {
                    subjects.splice(i, 1);
                    $('.input-subject option[value=' + oldSubject + ']').prop('disabled', false);
                    $('.input-subject option[value=' + oldSubject + ']').removeClass('d-none');
                }
            }
            subjects = JSON.stringify(subjects);
            $('input[name=scoresInfo]').attr('data-disabled', subjects);
            disableSubjects();
        }
    } else {
        var subjects = $('input[name=scoresInfo]').attr('data-disabled');
        subjects = JSON.parse(subjects)
        for (let i = 0; i < subjects.length; i++) {
            if (subjects[i] == oldSubject) {
                $('.input-subject option[value=' + oldSubject + ']').prop('disabled', false);
                $('.input-subject option[value=' + oldSubject + ']').removeClass('d-none');
                subjects.splice(i, 1);
            }
        }
        subjects.push(Number(subject));
        subjects = JSON.stringify(subjects);
        $('input[name=scoresInfo]').attr('data-disabled', subjects);
        disableSubjects();
    }
}

function setOldSubject($this) {
    oldSubject = $this.value;
}

function disableSubjects() {
    var subjects = $('input[name=scoresInfo]').attr('data-disabled');
    subjects = JSON.parse(subjects)
    for (let i = 0; i < subjects.length; i++) {
        $('.input-subject option[value=' + subjects[i] + ']').prop('disabled', true);
        $('.input-subject option[value=' + subjects[i] + ']').addClass('d-none');
    }
}

function deleteInputSubject($this) {
    var subjectRemove = $($this).parent().parent().find('.input-subject option:selected').val();

    if (subjectRemove != '') {
        subjectRemove = Number(subjectRemove);
        var subjects = $('input[name=scoresInfo]').attr('data-disabled');
        subjects = JSON.parse(subjects)
        for (let i = 0; i < subjects.length; i++) {
            if (subjects[i] == subjectRemove) {
                $('.input-subject option[value=' + subjectRemove + ']').prop('disabled', false);
                $('.input-subject option[value=' + subjectRemove + ']').removeClass('d-none');
                subjects.splice(i, 1);
            }
        }
        subjects = JSON.stringify(subjects);
        $('input[name=scoresInfo]').attr('data-disabled', subjects);
    }
    $($this).parent().parent().remove();
}

function resetForm() {

    var dataOldSubjects = $('input[name=scoresInfo]').attr('data-oldSubjects');
    dataOldSubjects = JSON.parse(dataOldSubjects)
    var subjects = JSON.parse('[]');

    for (let i = 0; i < dataOldSubjects.length; i++) {
        if (dataOldSubjects[i] != null) {
            subjects.push(Number(dataOldSubjects[i]));
        }
    }

    subjects = JSON.stringify(subjects);
    $('input[name=scoresInfo]').attr('data-disabled', subjects);

    $('.input-subject option').prop('disabled', false);
    $('.input-subject option').removeClass('d-none');
    disableSubjects();
}

function resetOptionDisableToSubmit() {
    $('.input-subject option').prop('disabled', false);
    $('.input-subject option').removeClass('d-none');
}
