<div class="row">

    @php
    if($attendingid == 1 ){
    $msg = "<p>Thank your for attending training at NYK-Fil Maritime E-Training, Inc.</p>
    <p>Your commitment to professional development is greatly appreciated. We trust that the knowledge and skills
        you will gain will serve you well in your maritime career.</p>
    <p>Thank you for choosing NYK-Fil Maritime E-Training, Inc.</p>";
    }else {
    $msg = "<p>We regret to inform you that you were marked as 'Not Attending' for the training program at NYK-Fil Maritime E-Training, Inc.</p>
    <p>If you have a valid reason for missing the training, please contact our support team to discuss further.</p>
    <p>Thank you.</p>";
    }
    @endphp

    <div class="alert alert-primary col-md-4 offset-md-4 mt-5" role="alert">
        {!! $msg !!}
    </div>


</div>