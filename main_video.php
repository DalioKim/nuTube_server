

<?php
//로컬로부터 영상목록에 대한 요청이 들어오면
//테이블에 접근해서 가장 최신 영상 10개를 들고온다.
//받아온 영상아이템의 정보들을 json형태로 만들어준다.
//클라이언트로 다시 보내준다.

$db = mysqli_connect("localhost", "root","suMMit88$","youtube");
$db->set_charset("utf8");

//로컬에서 보낸 요청횟수에 대한 변수를 저장하고, 정해진 수를 곱한다.
$count = $_POST['count'];
$count = (int)$count;
$count = $count*9;

//요청횟수에 을 곱한 수부터 데이터 X개를 들고온다.
//$sql = mysqli_query($db,"SELECT * from video order by idx desc limit 0, 9");
$sql = mysqli_query($db,"SELECT * from video order by idx desc");

//$row_num = mysqli_num_rows($sql);
//echo $row_num;


//영상정보목록을 담을 변수를 선언한다.
// 영상아이템에 들어갈정보
//String videoName,videoThumbnail,date,hit,title,id,userThumbnail=

$array = array();

while($row = mysqli_fetch_assoc($sql)){

//썸네일에 주소와 함꼐 파일명을 저장한다.
$videoName = "http://15.164.98.15/videos/".$row['videoName'];
$videoThumbnail = "http://15.164.98.15/videos/".$row['thumbnailName'];

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
'idx'=>urlencode($row['idx']),
'videoName'=>urlencode($videoName),
'videoThumbnail'=>urlencode($videoThumbnail),
'date'=>urlencode($date),
'hit'=>urlencode($hit),
'title'=>urlencode($row['title']),
'id'=>urlencode($row['userId']),
'subtitle'=>urlencode($row['subtitle']),
'tag'=>urlencode($row['tag']),
'theFirst'=>urlencode($row['theFirst']),
'startTime'=>urlencode($row['startTime']),
//'censorship'=>urlencode($row['censorship']),
'start'=>urlencode("false")
//'censorPhoto'=>urlencode($row['censorPhoto'])



);
array_push($array, $arrayMiddle);



}
//echo json_encode(array("response"=>"ok"));

echo urldecode(json_encode(array("videoItem"=>$array)));
//echo urldecode(json_encode(array("themaItems"=>$array)));






 ?>
