<?php


session_start();

//Create a session of username and logging in the user to the chat room
if(isset($_POST['username'])){
	$_SESSION['username']=$_POST['username'];
}

//Unset session and logging out user from the chat room
if(isset($_GET['logout'])){
	unset($_SESSION['username']);
	header('Location:index.php');
}

?>
<html>
<head>
	<title>Title Here</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="js/jquery-1.10.2.min.js" ></script>
  <script type="text/javascript" src="js/pubnub-3.7.13.min.js" ></script>
</head>
<body>
<div class='header'>
	<h1>
		Title Here
		<?php // Adding the logout link only for logged in users  ?>
		<?php if(isset($_SESSION['username'])) { ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; Welcome 
			<?php echo $_SESSION['username'] ?><a class='logout' href="?logout"> Logout</a>

		<?php } ?>
	</h1>

</div>

<div class='main'>
<?php //Check if the user is logged in or not ?>
<?php if(isset($_SESSION['username'])) { ?>
<div id='result'></div>

<div class='chatcontrols'>
	<form method="post" onsubmit="return submitchat();">
	<input type='text' name='chat' id='chatbox' autocomplete="off" required placeholder="ENTER CHAT HERE" />
	<input type='submit' name='send' id='send' class='btn btn-send' value='Send' />
	<input type='button' name='clear' class='btn btn-clear' id='clear' value='X' title="Clear Chat" />
</form>

<script>
// Javascript function to submit new chat entered by user
function submitchat(){
		if($('#chat').val()=='' || $('#chatbox').val()==' ') return false;
		$.ajax({
			url:'chat.php',
			data:{chat:$('#chatbox').val(),ajaxsend:true},
			method:'post',
			success:function(data){
				$('#result').html(data); // Get the chat records and add it to result div
				$('#chatbox').val(''); //Clear chat box after successful submition

				document.getElementById('result').scrollTop=document.getElementById('result').scrollHeight; // Bring the scrollbar to bottom of the chat resultbox in case of long chatbox
			}
		})
		return false;
};

// Function to continously check the some has submitted any new chat
setInterval(function(){
	$.ajax({
			url:'chat.php',
			data:{ajaxget:true},
			method:'post',
			success:function(data){
				$('#result').html(data);
			}

	})

}
,1000);


// Function to chat history
$(document).ready(function(){
	$('#clear').click(function(){
		if(!confirm('Are you sure you want to clear chat?'))
			return false;
		$.ajax({
			url:'chat.php',
			data:{username:"<?php echo $_SESSION['username'] ?>",ajaxclear:true},
			method:'post',
			success:function(data){
				$('#result').html(data);
			}
		})
	})
})
</script>
<?php } else { ?>
<div class='userscreen'>
	<form method="post">
		<input type='text' class='input-user' required placeholder="ENTER YOUR NAME HERE" name='username' />
		<input type='submit' class='btn btn-user' value='START CHAT' />
	</form>
</div>
<?php } ?>

</div>
</div>
<script>
  <?php
  $count = 0;
$myFile = "chatdata.txt";
$fh = fopen($myFile, 'r');
while(!feof($fh)){
    $fr = fread($fh, 8192);
    $count += substr_count($fr, 'br');
}
fclose($fh);
?>
  setInterval(function()
{
  <?php

  $count2 = 0;
$myFile = "chatdata.txt";
$fh = fopen($myFile, 'r');
while(!feof($fh)){
    $fr = fread($fh, 8192);
    $count2 += substr_count($fr, 'br');
}
fclose($fh);
  if($count2<$count) {
    ?>
    alert("under construction");
  
<?php } ?>

}, 3000);
</script>
</body>
<style>
.sidenav {
    height: 100%;
    width: 160px;
    position: fixed;
    z-index: 1;
    top: 0;
    right: 0;
    background-color: #111;
    overflow-x: hidden;
    padding-top: 20px;
}

.sidenav a {
    padding: 6px 8px 6px 16px;
    text-decoration: none;
    font-size: 10px;
    color: #818181;
    display: block;
}

.sidenav a:hover {
    color: #f1f1f1;
    font-size: 10px;
}
.sidenav form {
    padding: 6px 8px 6px 16px;
    text-decoration: none;
    font-size: 25px;
    color: #818181;
    display: block;
}


</style>
<div class="sidenav">
  <form action="upload.php" method="post" enctype="multipart/form-data">
    Upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>
<?php
$files = scandir('./uploads');
sort($files); // this does the sorting
foreach($files as $file){
   echo'<a href="uploads/'.$file.'"><br>'.$file.'</a>';
}
?>

</div>


</html>