

<?php
//

$db = mysqli_connect("localhost", "rayDalio","suMMit88$","youtube");
$db->set_charset("utf8");

//로컬에서 보낸 요청횟수에 대한 변수를 저장하고, 정해진 수를 곱한다.
$idx = $_POST['idx'];
$language = $_POST['language'];


$str = strcmp($language, "All");

//모든언어를 골랐을 시에 언어필터링을 하지않는다.
if(!$str){
  $sql = mysqli_query($db,"SELECT * from comment where video_idx = '" . $idx . "'  order by idx desc");

}else{
$sql = mysqli_query($db,"SELECT * from comment where video_idx = '" . $idx . "' AND language = '" . $language . "' order by idx desc");
}
//$row_num = mysqli_num_rows($sql);
//echo $row_num;


//영상정보목록을 담을 변수를 선언한다.
// 영상아이템에 들어갈정보
//String videoName,videoThumbnail,date,hit,title,id,userThumbnail=

$array = array();

while($row = mysqli_fetch_assoc($sql)){



//현재시간과 얼마나 차이가 나는지 구한다
$date = ($row['dateTime']);
$result = (strtotime(date('Y-m-d H:i:s')) - strtotime($date)) / 60;

// 결과 값은 소숫점으로 출력되는 경우가 있으므로 정수형(int)으로 캐스팅(형변환)
// 소숫점 이하 자리 제거는 floor나 number_format($result, false)를 이용하는 것도 가능
$date = (int) $result;
//분을 구한후 수치에 따라 분,시간,일자로 구분
if($date < 60){
$date = $date."분 전";
}else if($date < 1440){
$date = $date/60;
$date = round($date);
$date = $date."시간 전";

}else{
$date = $date/1400;
$date = round($date);
$date = $date."일 전";

}

$hit = ($row['hit']);

$arrayMiddle = array(
//pushing fetched data in an array
'id'=>urlencode($row['id']),
'content'=>urlencode($row['content']),
'date'=>urlencode($date)



);
array_push($array, $arrayMiddle);



}
//echo json_encode(array("response"=>"ok"));

echo urldecode(json_encode(array("commentItem"=>$array)));
//echo urldecode(json_encode(array("themaItems"=>$array)));






 ?>
