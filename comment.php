

<?php
//로컬로부터 영상목록에 대한 요청이 들어오면
//테이블에 접근해서 가장 최신 영상 10개를 들고온다.
//받아온 영상아이템의 정보들을 json형태로 만들어준다.
//클라이언트로 다시 보내준다.


//$string = "41:27";
//$test = "00:00 asd";
//$result = strstr($string, "/^([0-9]{2})\:([0-9]{2})$/");
//$result = strpos($test, ":");
//echo $result;
//if(strpos($string, ":") !== false) {
  //  echo "포함되어 있습니다만...";
//} else {
  //  echo "없군요.";
//}


//echo "추.$result";

//if( preg_match("/^([0-9]{2})\:([0-9]{2})$/", $string,$matches) ){
  //    echo"날짜 형식이 맞습니다";
    //    echo $matches[0];
//} else {
  //    echo"날짜 형식이 다릅니다.";
    //  echo $matches[0];
//}

$db = mysqli_connect("localhost", "","","youtube");
$db->set_charset("utf8");

//로컬에서 보낸 요청횟수에 대한 변수를 저장하고, 정해진 수를 곱한다.
$idx = $_POST['idx'];

//요청횟수에 을 곱한 수부터 데이터 X개를 들고온다.
$sql = mysqli_query($db,"SELECT * from comment where video_idx = '" . $idx . "' order by idx desc");

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
