<?php
// db username and password
$db_user = "root";
$db_pass = "root";
// auto-update (in seconds)
$refresh_timer = 30;

$conn = new mysqli('localhost', $db_user, $db_pass, 'ICBP');

if (isset($_POST['id']))
{
	// Checking connection
	if ($conn->connect_error) {
		echo 0;
		exit();
	}
	$id = mysqli_real_escape_string($conn, $_POST['id']);

	$result = $conn->query("SELECT `status` FROM `radio$id` ORDER BY time DESC LIMIT 1;");
	if (!$result)
	{
		echo 0;
		exit();
	}
	$row = $result->fetch_row();
	if (!$row)
	{
		echo 0;
		exit();
	}
	
	echo $row[0];
	$conn->close();
	exit();
}
else
{
	// Checking connection
	if (!$conn->connect_error)
	{
		for ($i = 0; $i < 10; $i++)
		{
			$id = $i + 1;
			$result = $conn->query("SELECT `status` FROM `radio$id` ORDER BY time DESC LIMIT 1;");
			if (!$result || !$row = $result->fetch_row())
			{
				$breaker[$i] = " off";
			}
			else
			{
				$status = $row[0] ? "" : "off";
				$breaker[$i] = " " + $status;
			}
		}
		$conn->close();
	}
	else
	{
		for ($i = 0; $i < 10; $i++)
			$breaker[$i] = " off";
	}
	
	if (isset($_POST['all']))
	{
		for ($i = 0; $i < 10; $i++)
			$breaker[$i] = strchr($breaker[$i], "off") ? 0 : 1;

		echo json_encode($breaker);
		exit();
	}
}
$refresh_timer *= 1000;
?>
<!DOCTYPE html>
<html>
<head>

<script src= "https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<style>
  div{
    float: left;
    color:#fff;
    font-size:40px;
  }

  h2{
    text-align: center;
  }

  .wrapper{
    position: absolute;
    top: 25%;
    left: 45%;
    margin-right: -50%;
    transform: translate(-50%, -50%);
  }

  .one{
    width: 125px;
    height:100px;
  }

  .two{
    width: 120px;
    height:75px;
    background:darkblue;
  }

  .three{
    width:120px;
    height:75px;
    background:blue;
  }

  .button{
    background-color: #4CAF50; /* Green */
    border: 1px solid green;
    color: white;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
    float: left;
    position: relative;
    left: 15%;
  }

  .status{
    background-color: #4CAF50; /* Green */
    border: 1px solid green;
    color: white;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
    float: left;
    position: relative;
    left: 15%;
  }

  .off {
    background: red;
  }

  .button:hover {
    background-color: #3e8e41;
  }
</style>
</head>

<body>
  <h2>ICBP Dashboard</h2>
  <div class="wrapper">
  <div class="one">
    <div class="two">
		<a href = "/breaker1/"><button class="button" data-id="1">Breaker 1</button></a>
		<button class="status <?php echo $breaker[0]; ?>">Status</button>
    </div>
    <div class="three">
		<a href = "/breaker2"><button class="button" data-id="2">Breaker 2</button></a>
		<button class="status <?php echo $breaker[1]; ?>">Status</button>
    </div>
  </div>

  <div class="one">
    <div class="two">
		<a href = "/breaker3"><button class="button" data-id="3">Breaker 3</button></a>
		<button class="status <?php echo $breaker[2]; ?>">Status</button>
    </div>
	<div class="three">
		<a href = "/breaker4"><button class="button" data-id="4">Breaker 4</button></a>
		<button class="status <?php echo $breaker[3]; ?>">Status</button>
    </div>
  </div>

  <div class="one">
    <div class="two">
                <a href = "/breaker5"><button class="button" data-id="5">Breaker 5</button></a>
                <button class="status <?php echo $breaker[4]; ?>">Status</button>
    </div>
    <div class="three">
                <a href = "/breaker6"><button class="button" data-id="5">Breaker 6</button></a>
                <button class="status <?php echo $breaker[5]; ?>">Status</button>
    </div>
  </div>

  <div class="one">
    <div class="two">
                <a href = "/breaker7"><button class="button" data-id="6">Breaker 7</button></a>
                <button class="status <?php echo $breaker[6]; ?>">Status</button>
    </div>
    <div class="three">
                <a href = "/breaker8"><button class="button" data-id="7">Breaker 8</button></a>
                <button class="status <?php echo $breaker[7]; ?>">Status</button>
    </div>
  </div>

  <div class="one">
    <div class="two">
                <a href = "/breaker9"><button class="button" data-id="8">Breaker 9</button></a>
                <button class="status <?php echo $breaker[8]; ?>">Status</button>
    </div>
    <div class="three">
                <a href = "/breaker10"><button class="button" data-id="9">Breaker 10</button></a>
                <button class="status <?php echo $breaker[9]; ?>">Status</button>
    </div>
  </div>
  </div>

  <script>
	$(document).ready(function() {
		setTimeout(executeQuery, <?php echo $refresh_timer; ?>);
	});
	function executeQuery() {
	  $.post("dashboard.php",
		{
			all: 1
		},
		function(data, status){
			var breaker = JSON.parse(data);
			for (var i = 0; i < breaker.length; i++)
			{
				var status_btn = $('.status')[i];
				if (breaker[i] == 0)
				{
					$(status_btn).addClass('off');
				}
				else if (breaker[i] == 1)
				{
					$(status_btn).addClass('on');	
				}
			}
		});
	  setTimeout(executeQuery, <?php echo $refresh_timer; ?>);
	}

	$(".button").click(function(){
		var status_btn = $(this).next('.status');
		$.post("dashboard.php",
		{
			id: $(this).data('id')
		},
		function(data, status){
			if (data == '0')
			{
				$(status_btn).addClass('off');
			}
			else if (data == '1')
			{
				$(status_btn).removeClass('off');	
			}
		})
		.fail(function(data, status) {
			if (!$(status_btn).hasClass('off'))
				$(status_btn).addClass('off');
		});
	});
  </script>
</body>
</html>
