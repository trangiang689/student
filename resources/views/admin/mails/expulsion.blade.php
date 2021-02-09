<div class="mailexpulsion" align="center">
    <div class="header">
        <h1>Hi! {{$user->name}}</h1>
    </div>
    <div class="content">
        <p>Điểm trung bình của bạn là: {{round($user->student->subjects->avg('pivot.score'),2)}}</p>
        <p>Do yêu cầu điểm trung bình phải trên: <span style="color: red">5.0</span> nên bạn không đặt yêu cầu và bị đuổi học!</p><br>
    </div>

    <div class="footer">
        <p>Thanks,</p>
        <p>{{$fromUser->name}}</p>
    </div>
</div>
