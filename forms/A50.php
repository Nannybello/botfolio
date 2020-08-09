<h1>บันทึกข้อความ A5</h1>
<table>
    <thead>
    <tr>
        <td rowspan="2">รายงานการฝึกอบรม ดูงาน ประชุม สัมนา</td>
        <td rowspan="2">Training Report 1 <br/> 
                        เอกสารหมายเลข 1</td>
    </tr>
    </thead>
    
</table>
<ol>
    <li>
        ชื่อ / นามสกุล {{info_first_name}} {{info_last_name}}
    </li>
    <li> 
        ตำแหน่ง {{position}} 
    </li>
    <li>
        สังกัด (คณะ / สถาบัน / สำนัก / กอง / ศูนย์ / หน่วย) {{affiliate}}
    </li>
    <li>
        ชื่อหลักสูตร {{courseName}} <br/>
        เพื่อ
        <input type="radio" name="purpose" id="purpose_1" value="training"/>
        <label for="purpose_1">ฝึกอบรม</label>
        
        <input type="radio" name="purpose" id="purpose_2" value="research"/>
        <label for="purpose_2">ดูงาน</label>

        <input type="radio" name="purpose" id="purpose_3" value="meeting"/>
        <label for="purpose_3">ประชุม</label>

        <input type="radio" name="purpose" id="purpose_4" value="seminar"/>
        <label for="purpose_4">สัมมนา</label> <br/>

        หน่วยงานที่จัด {{agency}}
    </li>
    <li>
        สถานที่ ณ {{location}}
    </Li>
    <li>
        ระยะเวลา/กำหนดวัน เวลา {{period}}
    </li>
    <li>
        ค่าใช้จ่าย <br/>
        ค่าลงทะเบียนเรียน {{registerFee}} <br/>
        ค่าเดินทาง {{fare}} <br/>
        ค่าใช้จ่ายอื่นๆ {expenses}} <br/>
    </li>
    <li>
        คุณวุฒิ/วุฒิบัตรที่ได้รับ {{qualification}}
    </li>
    <li>
        ปัญหา/อุปสรรคในการเข้าร่วมกิจกรรม {{barrier}}
    </li>
    <li>
        สรุปผลโดยย่อ<br/>
        - วัตถุประสงค์ {{purposeSummary}} <br/>
        - เนื้อหาโดย่อ {{briefContent}}
    </li>
    <li>
        ผลที่ได้รับ <br/>
        - ต่อตนเอง {{resultObtainedOnMyself}} <br/>
        ภายในระยะเวลา {{resultObtainedOnMyselfTime}} <br/>
        - ต่อองค์กร {{resultObtainedOnOganization}} <br/>
        ภายในระยะเวลา {{resultObtainedOnOganizationTime}} <br/>
        - อื่นๆ (ระบุ) {{resultObtainedOnOther}}
    </li>
    <li>
        ความคิดเห็นและข้อเสนอแนะ {{suggestion}}
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