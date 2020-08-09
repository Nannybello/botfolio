<h1>บันทึกข้อความ A5</h1>

<ol>
    <li>
        ชื่อ / นามสกุล {{info_first_name}} {{info_last_name}}
    </li>
    <li>
        ชื่อหลักสูตร {{courseName}} <br/>
        เพื่อ
        <input type="radio" name="purpose" id="purpose_1" value="training"/>
        <label for="purpose_1">ฝึกอบรม</label>
        
        <input type="radio" name="purpose" id="purpose_2" value="research"/>
        <label for="purpose_2">ดูงาน</label>
    </li>
    <li>
        สรุปผลโดยย่อ<br/>
        <input type="radio" name="purposeSummary" id="purposeSummary_1" value="purposeFor"/>
        <label for="purposeSummary_1">วัตถุประสงค์</label>
        {{purposeTrainingSummary}}

        <input type="radio" name="purposeSummary" id="purposeSummary_2" value="courseSummary"/>
        <label for="purposeSummary_2">วัตถุประสงค์</label>
        {{purposeTrainingSummary}}
    </li>
</ol>

<div>
    <p>ส่วนราชการ สาขาวิชา {{info_major}} คณะ {{info_faculty}}</p>
    <p>ที่ {{at}} วันที่ {{date}}</p>
    <p>เรื่อง {{courseName}}</p>
</div>

<hr/>

<p>เรียน คณะบดี</p>

<table>
    <thead>
    <tr>
        <td rowspan="2">หัวข้อเรื่อง</td>
        <td colspan="2">ผลสัมฤทธิ์</td>
        <td rowspan="2">มีการนำมาใช้</td>
    </tr>
    <tr>
        <td>ไม่มีการนำมาใช้</td>
        <td>มีการนำมาใช้</td>
    </tr>
    </thead>
    <tbody>
    <!--1-->
    <tr>
        <td>1.สามารถนำความรู้มาใช้ ...</td>
        <td>{{yes_1}}</td>
        <td>{{no_1}}</td>
        <td>{{how_1}}</td>
    </tr>
    <!--1-->
    </tbody>
</table>

<style>
    .my-form-paper thead td{
        text-align: center;
    }
    .my-form-paper td{
        border: 1px solid #000;
        padding: 5px;
    }
</style>