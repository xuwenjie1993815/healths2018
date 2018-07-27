<?php
return array(
    'EQUIPMENT'         =>array('1' => '血压计','2' => '血糖仪'),
    'ENTITY'            =>array('1' => '血压','2' => '血糖','3' => '血脂'),
    'SUGAR_DATE'        =>array('1' => '餐前','2' => '餐后','3' => '随机'),
    // 'patient_medical'   =>array('bloodYear'=>'1','isDiabetes' => '1','cerebralVascularDisease' => '1','heartDisease' => '1','kidneyDisease' => '1','peripheralAngiopathy' => '1','retinopathy' => '1','isSmoke' => '2','bloodFat' => '2','familyHypertension' => '2','bellyObesity' => '2','leftVentricularHypertrophy' => '3','iMT' => '3','pWV' => '3','aBI' => '3','eGFR' => '3','albumin' => '3'),
    'patient_medical'   =>array("1" => array('isDiabetes' => '糖尿病','cerebralVascularDisease' => '脑血管病','heartDisease' => '心脏疾病','kidneyDisease' => '肾脏疾病','peripheralAngiopathy' => '周围血管病','retinopathy' => '视网膜病变'),"2" => array('isSmoke' => '吸烟','bloodFat' => '血脂','familyHypertension' => '早发心血管病家族史','bellyObesity' => '腹型肥胖' ),"3" => array('leftVentricularHypertrophy' => '左心室肥厚','iMT' => '颈动脉','pWV' => '颈股动脉' ,'aBI' => 'ABI异常','eGFR' => 'eGFR异常','albumin' => '白蛋白异常')),
    'patient_history' =>"isDiabetes,isSmoke,bloodFat,familyHypertension,bellyObesity,leftVentricularHypertrophy,iMT,pWV,aBI,eGFR,albumin,cerebralVascularDisease,heartDisease,kidneyDisease,peripheralAngiopathy,retinopathy",
    // 接口地址
    'path' => '106.14.142.72:8763'
    // 'path' => '106.14.142.72:8765'
    // 'path' => '106.14.142.72:8709'
);