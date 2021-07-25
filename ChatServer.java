import java.io.*;
import java.net.*;
import java.util.*;
import java.util.ArrayList;


//1대 1채팅의 서버
public class ChatServer {


  static HashMap<String, ArrayList<ServerChatter>> hash;
 public static void main(String[] args) {
  // 현재 접속되어 있는 클라이언트 정보
  hash = new HashMap<String, ArrayList<ServerChatter>>();

  //새방이 생길때마다 리스트를 생성
  //ArrayList<ServerChatter> chatters = new ArrayList<ServerChatter>();
  ArrayList<ServerChatter> chatters = null;
  // 서버소켓 객체 선언
  ServerSocket serverSocket = null;
  Socket socket = null;

  // 접속된 순서 번호
  String roomNum;
  ServerChatter chatter = null;
  try{
   // 서버소켓 생성
   serverSocket = new ServerSocket(3306);
   while(true){
    System.out.println("***********클라이언트 접속 대기중*************");
    socket = serverSocket.accept();
    System.out.println("소켓접속 ");

    // 채팅 객체 생성
    chatter = new ServerChatter(socket,hash);
    //chatter.login();  // 대화명 입력 처리
    roomNum = chatter.login();
    //방이 존재하는지 여부를 hash에서 확인한다.
    //이미 방이 존재하면
    if(hash.containsKey(roomNum)){
        //해당 이름의 방의 소켓리스트에 소켓을 추가한다.
        synchronized (hash) {

      //  chatters = //new ArrayList<>(Arrays.asList(new ServerChatter (hash.get(roomNum))));
        //  new ArrayList<>(Arrays.asList(new ServerChatter(hash.get(roomNum))));


          //chatters = new ArrayList<>(hashmap.values(hash.get(roomNum)));
          //chatters = ArrayList<ServerChatter> hash.get(msg.split("/")[0].replace("a", "p"));


          //ArrayList<ServerChatter> chatters = new ArrayList<ServerChatter>(hash.get(roomNum));
      //  chatters =  <ServerChatter>Arrays.asList(hash.get(roomNum));
        //  chatters.add(chatter);

          chatters = hash.get(roomNum);
          System.out.println("추가 전 채팅 참여자 수"+chatters.size());
           chatters.add(chatter);
           chatter.no = chatters.size()-1;
           System.out.println("추가 후 채팅 참여자 수"+chatters.size());
           chatters.get(1).start();


      }



    }else{
      //새로운 방의 소켓리스트를 관리할 리스트를 생성하고 추가한다.
      chatters = new ArrayList<ServerChatter>();
      // 채팅 객체를 ArrayList에 저장한다.


      if(chatters.size()<2){
      chatters.add(chatter);
      chatter.no = chatters.size()-1;
}
      synchronized (hash) {
          hash.put(roomNum, chatters);
          chatters.get(0).start();


      }


    }








    // 접속된 순서에 따라 1대1 채팅을 시키기 위함
  //두명의 채터가 들어오면 쓰레드를 시작시킴

      synchronized (hash) {


        chatters = hash.get(roomNum);


        if(chatters.size() == 2){
          System.out.println("채팅인원 도달");

        chatters.get(0).getPartner();
        chatters.get(1).getPartner();

      }

    }




   }
  }catch(IOException e){
   System.out.println(e.getMessage());
  }finally{
   try{
    serverSocket.close();
   }catch(IOException e){
   }
  }
 }
}

// 소켓을 이용하여 클라이언트 1개와 직접 연결되어 있고
// ArrayList<> 인 chatters에 소속되어있는 또다른 소켓과 데이타를 주고받는 쓰래드 클래스
class ServerChatter extends Thread{
 // 클라이언트와 직접 연결되어 있는 소켓

 Socket socket;
 BufferedReader br; // 소켓으로부터의 최종 입력 스트림
 PrintWriter pw;  // 소켓으로부터의 최종 출력 스트림

 // 현재 서버에 접속된 전체 클라이언트 정보가 저장되어 있다.
 // 이들중 1개의 클라이언트와 채팅을 한다(1대1채팅)
 ArrayList<ServerChatter> chatters;
 HashMap<String, ArrayList<ServerChatter>> hash;



   // 접속된 순번 --> 현재 1대 1 채팅 대상을 구하기 위한 자신의 접속 순번
 String id; // 아이디(별칭)--> 대화메세지에 보여질 id(대화명) ==> 로그인처리에 의해 구함
 String roomNum;
 int no;
 Boolean partner = false;
 ServerChatter pair;
int pairNo;

//생성자
 public ServerChatter(Socket socket,HashMap<String, ArrayList<ServerChatter>> hash){

   this.socket = socket;
   this.hash = hash;


   //this.chatters = chatters;
   System.out.println("서버채팅 스레드 생성자 생성 ");

  // 소켓으로부터 최종 입출력 스트림 얻기
  try{
   br = new BufferedReader(new InputStreamReader(socket.getInputStream()));
   pw = new PrintWriter(socket.getOutputStream());
  }catch(IOException e){
   System.out.println(e.getMessage());
  }
 }

 // 대화명을 입력받는 처리 --> 확장되어지면 데이타베이스에 id/pass를 검색하여
 //         로그인 기능으로 확장할 수 있다.
 public String login(){
  try{

    //소켓으로부터 방번호와 아이디를 전달받고, 구분자(,)를 통해 구분해 저장한다.
   id = br.readLine();
   roomNum = id.substring(0, id.lastIndexOf(","));
   id = id.substring(id.lastIndexOf(",") + 1);
   //roomNum = "1";



  }catch(IOException e){
   System.out.println(e.getMessage());
   System.out.println("login()처리에서 예외 발생.....");
  }
  return roomNum;
 }

//쓰레드는 메세지를 받아서 출력하고 클라이언트에 보내는 역할만 한다.
 public void run(){


  //리스트를 관리하고 있는 hashMap을 통해 리스트를 얻어온다.



// 사용자가 채팅을 계속하는한 자기자신, 연결된 짝에게 읽은 메세지를 보내주면 된다.
//        0 짝수이면 1만큼 큰 요소  -----> 1
//                        1 홀수이면 1만큼 작은 요소 -----> 0



  try{
        String message = "";
        while(!message.equals("bye")){
             System.out.println(id +" 클라이언트가 메세지를 기다립니다.");
             message = br.readLine();
             System.out.println("받은 메세지 ==>" + id + ":" + message);

          // 자신과 직접 연결된 클라이언트에게 메세지를 다시 전송한다.
             //this.sendMessage(id + ": " + message);
          // 1대1채팅을 하도록 연결된 클라이언트에게 메세지를 전송한다.
          if(partner){
             pair.sendMessage(id + ": " + message);
           }
        }
  }catch(IOException e){
   System.out.println(e.getMessage());
   System.out.println("메세지를 수신하여 송신중 예외 발생....");
  }finally{
    //채팅방에서 나간 유저를 삭제
   chatters.remove(no);
   close();
   System.out.println("연결을 닫고 쓰레드 종료....");


  }
 }

 void getPartner()
 {
   System.out.println("파트너 메소드 호출");
   synchronized (hash) {
   //chatters =  new ArrayList<ServerChatter>(hash.get(roomNum));
   chatters = hash.get(roomNum);

 }
   pairNo = ( no % 2 == 0) ? no + 1 : no - 1;
   // 현재 클라이언트와 1대1 채팅을 클라이언트 구하기
   pair = chatters.get(pairNo);

   // 두개의 클라이언트가 동시에 채팅을 시작할 수 있도록 하기위해서
   this.sendMessage("start"); //시작 메세지 전송
   partner = true;
 }
 //메세지 전송을 위한 별도 메소드
 void sendMessage(String message){
  try{
   pw.println(message);
   pw.flush();
  }catch(Exception e){
   System.out.println(e.getMessage());
   System.out.println("sendMessage()에서 예외 발생....");
  }
 }


//close만들 위한 메소드
 public void close(){
  try{
   br.close();
   pw.close();
   socket.close();

   //채팅방에 상대방이 남아있을 경우 번호를 0번으로 교체
   int pairNo = ( no % 2 == 0) ? no + 1 : no - 1;
   ServerChatter pair = chatters.get(pairNo);
   pair.no = 0;
   //pair.close();
  }catch(Exception e){
   System.out.println("close()..도중 예외 발생!");
  }
 }
}
