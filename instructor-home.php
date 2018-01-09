<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

// Start of the output
$OUTPUT->header();

include("tool-header.html");
include("tool-header.html");
include("tool-js.html");

$OUTPUT->bodyStart();    

include("menu.php");
echo ('<h2>Quick Write</h2>');
$SetID = $_SESSION["SetID"];
$questions = $QW_DAO->getQuestions($SetID);	
$Total = count($questions);	
$_SESSION["Next"] = $Total +1;


if(isset($_GET["QID"])){$QID = $_GET["QID"];}
else{$QID = 0;}

echo(' <a class="btn btn-default" href="ViewAll.php" style="float:right;">View All Results</a> ');
	echo ('<br>Add questions to quickly collect feedback from your students.<br><br>');

	
foreach ( $questions as $row ) {
	
	
	echo('
	    <div class="panel-body" style="margin-bottom:3px;">           
		 <div class="col-sm-1 noPadding text-center" ><h4>'.$row["QNum"].'</h4></div>
		 <div class="col-sm-8 noPadding" >
			 <div style="background-color:lightgray; width:100%;padding:10px; min-height:60px;border:1px gray solid; " >'.$row["Question"].'</div>
         </div>			

		<div class="col-sm-3 noPadding" style="float:right; width:225px;">
			<a class="btn btn-danger pull-right" href="actions/Delete.php?QID='.$row["QID"].'" onclick="return ConfirmDelete();"><span class="fa fa-trash"></span></a>');
	
			echo(' <a href="#Edit_'.$row['QID'].'"  class="btn btn-success"  data-toggle="modal" >Edit </a>');
	
	
	
	?>

<div class="modal fade" id="<?php echo $row['QID']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" style="margin-top:100px;">
			<div class="modal-content" style="width:100%;  overflow-y: scroll; height:700px;">
			
				<div class="modal-header" style="background-color: #427DB0; color:white;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<?php echo ('<h3>Q'.$row["QNum"].' '.$row["Question"].'</h3>');?>
					</div><div>
				<?php
	
			$QID = $row['QID'];
				$StudentList = $QW_DAO->ReportByQID($SetID, $QID);	
	
	$rNum=0;
				foreach ( $StudentList as $row3 ) {

					echo('
						<div class="panel-body " style="border:1px solid gray;">           
							<div class="col-sm-2 noPadding"><b>'.$row3["FirstName"].' '.$row3["LastName"].'</b>');				
					echo('</div><div class="col-sm-10 noPadding">');
									$UserID = 	$row3["UserID"];
									$A="";													
									$Data = $QW_DAO->Review($QID, $UserID);	
					
					
					
									foreach ( $Data as $row2 ) {

													$A= $row2["Answer"];
													$Date1 = $row2["Modified"];
													$rNum++;


												}


										echo ($A); 

							
							echo ('</div>
						</div>


						'); 


				}
					?>
					
					
					
				
				</div>		
			  </div>
				
				
	 
		</div>		   

	</div>



<?php
	
	
	
		
	echo(' <a href="#'.$row['QID'].'"  class="btn btn-primary"  data-toggle="modal" style="width:100px;">Report ('.$rNum.') </a>');
			
	 echo('	</form> </div>
		   </div>
		
           

        '); 
	
?>





		



		<div class="modal fade" id="Edit_<?php echo $row['QID']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" style="margin-top:200px;">
			
				
			
			<div class="modal-content" style="width:100%; padding:10px;height:180px;">
				
				<?php
	echo ('
			<form method="post" action="actions/AddQ_Submit.php">
			<input type="hidden" name="QID" value="'.$row["QID"].'"/>
			<input type="hidden" name="Flag" value="2"/>
			
			<textarea class="form-control" name="Question" rows="3" autofocus required style="resize:none;">'.$row["Question"].'</textarea>');
			
	
	  echo('<br><br>
	  
	  <button type="button" class="btn btn-danger" data-dismiss="modal" style="width:70px; float:right;" >Cencel</button>
	  <input type="submit" class="btn btn-success" style="width:70px; float:right; margin-right:10px;" value="Save">');
					?>
				
					
						
			  </div>
		</div>
	</div>


	
	<?php
	
	
}
	

if($_GET["Add"]){
	
	echo('<form method="post" action="actions/AddQ_Submit.php">
	    <div class="panel-body" style="margin-bottom:3px;">           
			<div class="col-sm-1 noPadding text-center" > <h4>'.$_SESSION["Next"].'</h4></div>
			<div class="col-sm-8 noPadding" >
			   <textarea class="form-control" name="Question" id="Question" rows="3" autofocus required></textarea>
			
            <input type="hidden" name="QNum" value="'.$_SESSION["Next"].'"/>
			<input type="hidden" name="Flag" value="1"/>
					
			</div>					

			<div class="col-sm-3 noPadding" style="float:right; width:225px;">
			<a class="btn btn-danger pull-right disabled" href="" ><span class="fa fa-trash" ></span></a>
			<input type="submit" class="btn btn-success" value="Save" >');
	
			
	 echo('	</form> </div>
		   </div>
		
           

        '); 
	
	
	
	
	
	
	
	
	
	

}


	echo ('<a class="btn btn-success" href="instructor-home.php?Add=1" style="margin:50px;">Add Question</a>');
	

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();


?>