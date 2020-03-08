<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Botfolio</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
          integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    x is {{ $x }}
    <div class="my-form">
        {!! $formContent !!}
    </div>
</div>

<style>
    .my-form{
        border: 1px solid #888;
        background: antiquewhite;
        padding: 20px;
    }
    .my-form input{
        border: none;
        border-bottom: 1px dashed #888;
        background: none;
        color: #888888;
        font-family: monospace;
    }
</style>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ"
        crossorigin="anonymous"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
        integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd"
        crossorigin="anonymous"></script>

<script>
    $('.my-form').each(function () {
        let $form = $(this)
        let html = $form.html()
        let inputs = html.match(/{([^}]+)}\}/g).map(res => res.replace(/{|}/g, ''))
        //console.log(inputs)
        for (let input of inputs) {

            let [inputName, ...attrs] = input.split('|')
            attrs = attrs.map(str => {
                let [attr, ...value] = str.split('=')
                return {
                    attr, value: value[0],
                }
            })
            //console.log({tagName: inputName, attrs})

            let $wrapper = $('<div>')
            let ele = $('<input>', {type: 'text', name: inputName})
            for(let attr of attrs){
                ele.attr(attr.attr, attr.value)
            }
            $wrapper.append(ele)
            html = html.replace('\{\{' + input + '\}\}', $wrapper.html())
        }
        //console.log(html)
        $form.html(html)

        let $input = $form.find('input')
        $input.on('blur', function(){
            let ele = $(this)
            let value = ele.val()
            let name = ele.attr('name')
            $input.filter(`[name=${name}]`).not(this).val(value)
        })
    })
</script>
</body>
</html>