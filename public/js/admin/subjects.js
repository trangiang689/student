var countInputFaculties = 0;
var oldFaculty = '';
$(document).ready(function () {
    $(".add-input-faculty").on("click", function ($this) {
        var dataAdd = $('#dataInputAdd').html();
        $('.group-input-faculty > div').append(dataAdd);
        $('.group-input-faculty .input-faculty[name= ""]').attr('name', 'faculties[]');
        disableFaculties();
    });

    $(".btn-resetForm").on("click", function () {
        resetForm();
    });

    disableFaculties();

    $("#formEditSubject").submit(function () {
        resetOptionDisableToSubmit(this);
    });
});
reloadPageCreateSubjects();
disableFaculties();

function reloadPageCreateSubjects() {
    var dataOldFaculties = $('input[name=facultiesInfor]').attr('data-oldfaculties');
    dataOldFaculties = JSON.parse(dataOldFaculties)
    var faculties = $('input[name=facultiesInfor]').attr('data-disabled');
    faculties = JSON.parse(faculties);

    dataOldFaculties.forEach(function(value){
        if (value != null) {
            faculties.push(Number(value));
        }
    });
    faculties = JSON.stringify(faculties);
    $('input[name=facultiesInfor]').attr('data-disabled', faculties);

    disableFaculties();
}

function changeChoseFaculty($this) {
    var faculty = $this.value;
    if (faculty == "") {
        if (oldFaculty == "") {
        } else {
            var faculties = $('input[name=facultiesInfor]').attr('data-disabled');
            faculties = JSON.parse(faculties)
            for (let i = 0; i < faculties.length; i++) {
                if (faculties[i] == oldFaculty) {
                    faculties.splice(i, 1);
                    $('.input-faculty option[value=' + oldFaculty + ']').prop('disabled', false);
                    $('.input-faculty option[value=' + oldFaculty + ']').removeClass('d-none');
                }
            }
            faculties = JSON.stringify(faculties);
            $('input[name=facultiesInfor]').attr('data-disabled', faculties);
            disableFaculties();
        }
    } else {
        var faculties = $('input[name=facultiesInfor]').attr('data-disabled');
        faculties = JSON.parse(faculties)
        for (let i = 0; i < faculties.length; i++) {
            if (faculties[i] == oldFaculty) {
                $('.input-faculty option[value=' + oldFaculty + ']').prop('disabled', false);
                $('.input-faculty option[value=' + oldFaculty + ']').removeClass('d-none');
                faculties.splice(i, 1);
            }
        }
        faculties.push(Number(faculty));
        faculties = JSON.stringify(faculties);
        $('input[name=facultiesInfor]').attr('data-disabled', faculties);
        disableFaculties();
    }
}

function setOldFaculty($this) {
    oldFaculty = $this.value;
}

function disableFaculties() {
    var faculties = $('input[name=facultiesInfor]').attr('data-disabled');
    faculties = JSON.parse(faculties)
    for (let i = 0; i < faculties.length; i++) {
        $('.input-faculty option[value=' + faculties[i] + ']').prop('disabled', true);
        $('.input-faculty option[value=' + faculties[i] + ']').addClass('d-none');
    }
}

function deleteInputFaculty($this) {
    var facultyRemove = $($this).parent().parent().find('.input-faculty option:selected').val();

    if (facultyRemove != '') {
        facultyRemove = Number(facultyRemove);
        var faculties = $('input[name=facultiesInfor]').attr('data-disabled');
        faculties = JSON.parse(faculties)
        for (let i = 0; i < faculties.length; i++) {
            if (faculties[i] == facultyRemove) {
                $('.input-faculty option[value=' + facultyRemove + ']').prop('disabled', false);
                $('.input-faculty option[value=' + facultyRemove + ']').removeClass('d-none');
                faculties.splice(i, 1);
            }
        }
        faculties = JSON.stringify(faculties);
        $('input[name=facultiesInfor]').attr('data-disabled', faculties);
    }
    $($this).parent().parent().remove();
}

function resetForm() {

    var dataOldFaculties = $('input[name=facultiesInfor]').attr('data-oldfaculties');
    dataOldFaculties = JSON.parse(dataOldFaculties)
    var faculties = JSON.parse('[]');

    for (let i = 0; i < dataOldFaculties.length; i++) {
        if (dataOldFaculties[i] != null) {
            faculties.push(Number(dataOldFaculties[i]));
        }
    }

    faculties = JSON.stringify(faculties);
    $('input[name=facultiesInfor]').attr('data-disabled', faculties);

    $('.input-faculty option').prop('disabled', false);
    $('.input-faculty option').removeClass('d-none');
    disableFaculties();
}

function resetOptionDisableToSubmit() {
    $('.input-faculty option').prop('disabled', false);
    $('.input-faculty option').removeClass('d-none');
}
