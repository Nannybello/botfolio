<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>

<h1>บันทึกข้อความ</h1>

<div>
    <p>ส่วนราชการ สาขาวิชา {{major}} คณะ {{faculty}}</p>
    <p>ที่ {{at}} วันที่ {{date}}</p>
    <p>เรื่อง {{courseName}}</p>
</div>

<hr/>

<p>เรียน คณะบดี</p>

<p>ด้วนชื่อ-นามสกุล {{name}} อาจารย์ประจำสาขา {{staffId}}</p>
<p>ภาควิชา {{department}} คณะวิทยาศาสตร์และเทคโนโลยี มีความประสงค์เข้าร่วมอบรม</p>

test: {{faculty|class=a|value=ข้อความเปลี่ยนไม่ได้|disabled=disabled}}

</body>
</html>