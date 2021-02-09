<form action="{{route('getStudent',['student_code' => 'sv1'])}}">
    @csrf
    @method('GET')
    <input type="text" name="student_code"><br>
    <input type="submit">
</form>
