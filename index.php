<?php 
session_start();

class Bee {
	
	public function reset($msg = null){
		unset($_SESSION['bees']);
		return $msg.'Press the button to start the game! <form method="post"><input type="hidden" name="op" value="start"><input type="submit" value="Start"></form>';
	}

	public function start() {
			$_SESSION['bees'] = array(
									0 => array("queen", "life" => 100, "hit" => 8, "bees" => 1, "lifestart" => 100),
									1 => array("worker", "life" => 75, "hit" => 10, "bees" => 5, "lifestart" => 75),
									2 => array("drone", "life" => 50, "hit" => 12, "bees" => 8, "lifestart" => 50)
									);
	        return 'Game started. Hit the bee! <form method="post"><input type="hidden" name="op" value="hit"> <input type="submit" value="Hit"></form>';
	}

	public function hit() {

	    $msg = '';
	    if($_SESSION['bees'])
	        $rd = array_rand($_SESSION['bees'],1);
	    else
	        $this->start('Game will start again');
	     	$_SESSION['bees'][$rd]['life'] = $_SESSION['bees'][$rd]['life'] - $_SESSION['bees'][$rd]['hit'];
	     if($_SESSION['bees'][$rd]['life']<1) {
	        $_SESSION['bees'][$rd]['bees'] -=1;
	        $_SESSION['bees'][$rd]['life'] = $_SESSION['bees'][$rd]['lifestart'];

	        if($_SESSION['bees'][$rd][0] == "queen") {
	            return $this->reset('The game is over, the Queen is dead. ');
	        }else{
	        	$msg .= 'One "' . $_SESSION['bees'][$rd][0] . '" is dead';
	        }
	    }
	    if($_SESSION['bees'][$rd]['bees']<1){ 
	        $msg = 'The "' . $_SESSION['bees'][$rd][0] . '" team is dead';
	         unset($_SESSION['bees'][$rd]);
	    }
	    //print_r($_SESSION['bees']);
	    return 'Hit again!!<form method="post"><input type="hidden" name="op" value="hit"> <input type="submit" value="Hit"></form>' . $msg;
	}


	public function showScore(){
		if(!$_SESSION['bees']) return;
		
		$tot = count($_SESSION['bees']);
		$content = $this->getBee($tot);
		echo "";


		return $content;
	}

	private function getBee($n){

		for($i=0; $i<$n;$i++){
			if($_SESSION['bees'][$i]['bees'] == 1){
				$response .= "<br />There is 1 ".$_SESSION['bees'][$i][0]." with ".$_SESSION['bees'][$i]['life']." of life.";
			}else{
				$response .= "<br />There are ". $_SESSION['bees'][$i]['bees']." ".$_SESSION['bees'][$i][0].". The actual ".$_SESSION['bees'][$i][0]." has ".$_SESSION['bees'][$i]['life']." of life.";
			}
		}
		return $response;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Hit the Bee!</title>
</head>
<body>
<?php                                               
        $game = new Bee();

        switch($_POST['op']){
        	case 'start':
        		echo $game->start();
        		break;
        	case 'hit':
        		echo $game->hit();
        		break;
        	default:
        		echo $game->reset();
        		break;
        }
        echo $game->showScore();
    ?>
</body>
</html>