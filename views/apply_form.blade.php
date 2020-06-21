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
    <h1>Apply Form</h1>
    <table>
        <tr>
            <th>id</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>name</th>
            <td>{{ $user->info_first_name }} {{ $user->info_last_name }}</td>
        </tr>
        <tr>
            <th>major</th>
            <td>{{ $user->info_major }}</td>
        </tr>
        <tr>
            <th>faculty</th>
            <td>{{ $user->info_faculty }}</td>
        </tr>
        <tr>
            <th>position</th>
            <td>{{ $user->info_position }}</td>
        </tr>
    </table>
    <form class="my-form" method="post" action="applyform-submit">
        <input type="hidden" name="approval_type_id" value="{{ $approval_type_id }}"/>
        <input type="hidden" name="form_type" value="{{ $form_type->id }}"/>
        <input type="hidden" name="token" value="{{ $token }}"/>
        <div class="my-form-paper">
            {!! $formContent !!}
        </div>
        <div>
            <h2>แนบเอกสาร</h2>
            <ul class="list-group">
                @foreach($files as $file)
                    <li class="list-group-item">
                        <input class="form-check-input" type="checkbox" name="attach_files[]" value="{{ $file->id }}"
                               id="f{{ $file->id }}">
                        <label class="form-check-label" for="f{{ $file->id }}">
                            {{ $file->filename_original }}
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
        <br/>
        @if($h4_list)
            <h3>เลือกคนอนุมัติ H4</h3>
            <select name="H4_approver_id">
                @foreach($h4_list as $h4)
                    <option value="{{ $h4->id }}">{{ $h4->info_first_name }} {{ $h4->info_last_name }}</option>
                @endforeach
            </select>
        @endif


        @if($h3_list)
            <h3>เลือกคนอนุมัติ H3</h3>
            <select name="H3_approver_id">
                @foreach($h3_list as $h3)
                    <option value="{{ $h3->id }}">{{ $h3->info_first_name }} {{ $h3->info_last_name }}</option>
                @endforeach
            </select>
        @endif


        @if($h2_list)
            <h3>เลือกคนอนุมัติ H2</h3>
            <select name="H2_approver_id">
                @foreach($h2_list as $h2)
                    <option value="{{ $h2->id }}">{{ $h2->info_first_name }} {{ $h2->info_last_name }}</option>
                @endforeach
            </select>
        @endif


        @if($h1_list)
            <h3>เลือกคนอนุมัติ H1</h3>
            <select name="H1_approver_id">
                @foreach($h1_list as $h1)
                    <option value="{{ $h1->id }}">{{ $h1->info_first_name }} {{ $h1->info_last_name }}</option>
                @endforeach
            </select>
        @endif


        @if($followUpRange)
            <h3>ติดตามผลภายใน</h3>
            <select name="follow_up">
                @foreach($followUpRange as $month)
                    <option value="{{ $month }}">{{ $month }} เดือน</option>
                @endforeach
            </select>
        @endif

        @if($parent_id)
            <input type="hidden" name="parent_id" value="{{ $parent_id }}"/>
        @endif

        <input type="submit" class="btn btn-primary btn-lg btn-block mt-4" value="ส่งคำขออนุมัติ"/>
    </form>
    <hr/>
</div>

<style>
    .my-form-paper {
        border: 1px solid #888;
        background: antiquewhite;
        padding: 20px;
    }

    .my-form-paper input {
        border: none;
        border-bottom: 1px dashed #888;
        background: none;
        color: #888888;
        font-family: monospace;
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
            let ele = $('<input>', {type: 'text', name: `data_${inputName}`})
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
        let userData = JSON.parse('<?= json_encode($user->toArray()) ?>')
        let $form = $('.my-form');
        for (let field in userData) {
            $form.find(`input[name=data_${field}]`).val(userData[field])
        }
    })

    $(function () {
        let prefields = JSON.parse('<?= /** @var Array $prefields */ json_encode($prefields) ?>')
        let $form = $('.my-form');
        for (let field in prefields) {
            $form.find(`input[name=data_${field}]`).val(prefields[field])
        }
    })
</script>
</body>
</html>