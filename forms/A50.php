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
<p> 
(ผู้รายงาน) {{info_first_name}} {{info_last_name}} <br/>
วันที่ {{date}}
</p>
<table>
<thead>
    <tr>
        <th>การติดตามผลการฝึกอบรม ดูงาน ประชุม / สัมมนา ภายในประเทศ</th>
        <td>
            ให้รายงานผลการนำความรู้และทักษะที่ได้จากกาพัฒนามาใช้ในการเรียนการสอน 
            หรือพัฒนาปรับปรุงงานที่ได้รับผิดชอบ ในแบบฟอร์มติดตามผลการรายงานการฝึกอบรม 
            ดูงาน ประชุม สัมมนา (เอกสารหมายเลข 2) โดยขอให้เจ้าหน้าที่หน่วยงานต้นสังกัด
            ส่งแบบฟอร์มติดตามผลการรายงานการฝึกอบรม ดูงาน ประชุมม สัมมนา ให้ผู้บังคับ
            บัญชาติดตามผลภายหลังการพัฒนา 6 เดือน หรือ 9 เดือน (เลือกรายงาน
            อย่างน้อย 1 ครั้ง)    
        </td>
        <input type="radio" name="followUp" id="followUp_1" value="6month"/>
        <label for="followUp_1">6 เดือน ภายหลังจากการพัฒนา</label>
        
        <input type="radio" name="followUp" id="followUp_2" value="9month"/>
        <label for="followUp_2">9 เดือน ภายหลังจากการพัฒนา</label>
    </tr>
    </thead>
</table>
หมายเหตุ
<ol>
    <li>
        ให้ผู้รับการฝึกอบรม ดูงาน ประชุม สัมมนา ส่งรายงานการฝึกอบรม Training Report 1
        (เอกสารหมายเลข 1) ให้แก่ผู้บังคับบัญชา ภายใน 5วันทำการ หลังเสร็จสิ้นการฝึกอบรม
    </li>
    <li>
        ใผู้ได้รับการพัฒนากรอกแบบฟอร์มติดตามผลการรายงานการฝึกอบรม ดูงาน ประชุม สัมมนา
        Follow-Up report on the training 2 (เอกสารหมายเลข 2) ให้เจ้าหน้าที่ของ
        หน่วยงานต้นสังกัด เพื่อให้ผู้บังคับบัญชาติดตามผลภายหลังการพัฒนา (เลือกรายงานอย่างน้อย 
        1 ครั้ง)
    </li>
</ol>
<style>
    .my-form-paper thead td{
        text-align: center;
    }
    .my-form-paper td{
        border: 1px solid #000;
        padding: 5px;
    }
    p {text-align: right;
    }
</style>