<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Botfolio</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
    <h1>Reject Form</h1>
    <form action="/rejectform" method="post">
        <input type="hidden" name="token" value="{{ $token }}"/>
        <input type="hidden" name="approval_instance_id" value="{{ $approval_instance_id}}"/>
        <div class="form-group">
            <label for="in1">Reason</label>
            <textarea class="form-control" id="in1" name="reason"></textarea>
            <small id="emailHelp" class="form-text text-muted">reason for reject this approve form.</small>
        </div>
        <button type="submit" class="btn btn-danger">Reject</button>
    </form>
</div>
</body>
</html>