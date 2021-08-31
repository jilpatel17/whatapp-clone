<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php"; ?>
<body>
  
  <div class="wrapper">
    
    <div id="calling" style="display: none;">
    
      <div class="meet-area">
          <!-- Remote Video Element-->
          <video id="remote-video" width="450px"></video>

          <!-- Local Video Element-->
          <video id="local-video" width="150px" height="150px"></video>
      </div>
    </div>


    <section class="chat-area" id="chat-area">
      <div id="alert" style="text-align:center;background-color:green;padding:9px 10px;display:none">
          <!-- <button onclick="my()">accept</button> -->
      </div>
      <header>
        <?php 
          $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }else{
            header("location: users.php");
          }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo $row['img']; ?>" alt="">
        <div class="details">
          <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
          <p><?php echo $row['status']; ?></p>
        </div>
        <button class="videocall" onclick="videocall()"><i class="fas fa-video fa-2x"></i></button>
      </header>
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js"></script>
  <script src="javascript/chat.js"></script>
<script>
  const PRE = "DELTA"
    const SUF = "MEET"
    var room_id;
    var getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    var local_stream;
    function fetchCall(){
      $.ajax({
        url:'fetch.php',
        method:'GET',
        success:function(data){
          if(data!="not fetch")
          {
            
          var output = `<button id='acceptcall' style='margin-left:6px;border-radius:50%;background-color:purple;color:white;padding:3px 6px;border:none' data-id=${data}><i class='fas fa-phone 2x'></i></button>
          <button id='declinecall' style='margin-left:6px;border-radius:50%;background-color:red;color:white;padding:3px 6px;border:none' data-id=${data}><i class='fas fa-phone-slash 2x'></i></button>`;
            $('#alert').html(output);
            $('#alert').css("display",'block');
          }
        }
      });
    }
    

    setInterval(function(){
      fetchCall();
    },1000);

    $(document).on("click","#acceptcall",function(){
      
    document.getElementById('chat-area').style.display= 'none';
    document.getElementById('calling').style.display= 'inherit';
      var calling_id = $(this).data('id');
      console.log("Joining Room")
            
            let room = calling_id;
        
            room_id = PRE+room+SUF;
            console.log(room_id)
            let peer = new Peer()
            peer.on('open', (id)=>{
                console.log("Connected with Id: "+id)
                getUserMedia({video: true, audio: true}, (stream)=>{
                    local_stream = stream;
                    setLocalStream(local_stream)
                   
                    let call = peer.call(room_id, stream)
                    call.on('stream', (stream)=>{
                        setRemoteStream(stream);
                    })
                }, (err)=>{
                    console.log(err)
                });

            });

                    function setLocalStream(stream){
                        
                        let video = document.getElementById("local-video");
                        video.srcObject = stream;
                        video.muted = true;
                        video.play();
                    }
                    function setRemoteStream(stream){
                    
                        let video = document.getElementById("remote-video");
                        video.srcObject = stream;
                        video.play();
                    }
    });

    


  function videocall(){
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const uid = urlParams.get('user_id');
    
    document.getElementById('chat-area').style.display= 'none';
    document.getElementById('calling').style.display= 'inherit';
    $.ajax({
      url:'video.php',
      method:'POST',
      data:{uid:uid},
      success:function(data){
        console.log(data);
        console.log("Creating Room")
                    let room = uid;
                    
                    room_id = PRE+room+SUF;
                    let peer = new Peer(room_id)
                    peer.on('open', (id)=>{
                        console.log("Peer Connected with ID: ", id)
                        
                        getUserMedia({video: true, audio: true}, (stream)=>{
                            local_stream = stream;
                            setLocalStream(local_stream)
                        },(err)=>{
                            console.log(err)
                        })
                        
                    })
                    peer.on('call',(call)=>{
                        call.answer(local_stream);
                        call.on('stream',(stream)=>{
                            setRemoteStream(stream)
                        });
                    });
                    
                    function setLocalStream(stream){
                        
                        let video = document.getElementById("local-video");
                        video.srcObject = stream;
                        video.muted = true;
                        video.play();
                    }
                    function setRemoteStream(stream){
                    
                        let video = document.getElementById("remote-video");
                        video.srcObject = stream;
                        video.play();
                    }
      }
    });


    
    
  }

</script>
</body>
</html>
