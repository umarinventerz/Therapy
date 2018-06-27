<?php
function sanitizeString($cnx, $var) {
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return $cnx->real_escape_string($var);
}
  
function GetNumberOfQuestions($cnx, $email) {
	$query = "SELECT COUNT(id) FROM comunications_portal_patients WHERE send_to = '" . $email. "'";
	$output = mysqli_query ($cnx, $query);
	if(!$output) echo "ERROR QUERY: " . mysqli_error($cnx);		 
	$row = mysqli_fetch_array($output, MYSQLI_NUM);
	return $row[0];
}	

function GetPage($cnx) {
	return (isset($_GET{'page'})) ? (sanitizeString($cnx, $_GET{'page'})) : (1);
}

function GetOffSet($page, $rec_limit) {
	return (isset($_GET{'page'})) ? ($rec_limit * ($page - 1)) : (0);
}

function GetLeftRecords($rec_count, $page, $rec_limit) {
	$left_rec = $rec_count - ($page  * $rec_limit); 
	if ($left_rec < 0) $left_rec = 0;	 
	return $left_rec;
}

function PutNavBar($actual_link, $rec_limit, $page, $left_rec) {			
	if (($page == 1) && ($left_rec > 0)) {  // only next
		$next = $page + 1;
		echo "<a href = \"$actual_link?page=$next\">Next " . (($left_rec > $rec_limit) ? ($rec_limit) : ($left_rec)) . " Records</a>";
	} elseif (($page > 1) && ($left_rec > 0)) {  // both Last and Next
		$next = $page + 1;
		$prev = $page - 1;
		echo "<a href = \"$actual_link?page=$prev\">Last $rec_limit Records</a> |";
		echo "<a href = \"$actual_link?page=$next\">Next " . (($left_rec > $rec_limit) ? ($rec_limit) : ($left_rec)) . " Records</a>";
	} elseif ($page > 1) {  // only Last
		$prev = $page - 1;
		echo "<a href = \"$actual_link?page=$prev\">Last $rec_limit Records</a>";	 
	}
}
?>