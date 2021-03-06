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

<!--
<? /** @var TYPE_NAME $rawData */
print_r($rawData); ?>
        -->

<div class="container">
    <h1>Apply Form</h1>
    <div class="my-form-paper">
        {!! $formContent !!}
    </div>
    <hr/>
</div>

<style>

    .my-form-paper {
    }

    .my-form-paper p {
    }
</style>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>

<script>
    $('.my-form-paper').each(function () {
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
            let ele = $('<span>', {type: 'text', id: `data_${inputName}`})
            for (let attr of attrs) {
                ele.attr(attr.attr, attr.value)
            }
            $wrapper.append(ele)
            html = html.replace('\{\{' + input + '\}\}', $wrapper.html())
        }
        //console.log(html)
        $form.html(html)

        let $input = $form.find('input')
        $input.on('blur', function () {
            let ele = $(this)
            let value = ele.val()
            let name = ele.attr('name')
            $input.filter(`[name=data_${name}]`).not(this).val(value)
        })
    })

    $(function () {
        let data = JSON.parse('<?= json_encode($data) ?>')
        let $form = $('.my-form-paper');
        for (let field in data) {
            let id = `data_${field}`
            $form.find(`input[id=${id}]`).text(data[field])
        }
    })
</script>
</body>
</html>