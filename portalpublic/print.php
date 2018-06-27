
<?php

session_start();
require_once("conex.php");

$xls_filename = 'export_'.date('Y-m-d').'.xls'; // Define Excel (.xls) file name
    
    $sql = "select Pat_id, Patient_name , DOB, Pri_ins 
 
from  



(Select Distinct  Pat_id, concat(Last_name,', ',First_name) as Patient_name, DOB, Pri_ins from patients_dlc  


union
                                
Select Distinct Pat_id, concat(Last_name,', ',First_name) as Patient_name , DOB, Pri_ins from patients_kqt  

            union
                            
                            
Select Distinct  Pat_id, concat(Last_name,', ',First_name) as Patient_name , DOB, Pri_ins from patients_dlcquality 


) as r 
 
order by  Pri_ins, Patient_name";

 
// Execute query
$Connect = conectar();
$result = @mysql_query($sql,$Connect) ;
 
// Header info settings
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache");
header("Expires: 0");
 
/***** Start of Formatting for Excel *****/
// Define separator (defines columns in excel &amp; tabs in word)
$sep = "\t"; // tabbed character
 
// Start of printing column names as names of MySQL fields
for ($i = 0; $i<mysql_num_fields($result); $i++) {
  echo mysql_field_name($result, $i) . "\t";
}
print("\n");
// End of printing column names
 
// Start while loop to get data
while($row = mysql_fetch_row($result))
{
  $schema_insert = "";
  for($j=0; $j<mysql_num_fields($result); $j++)
  {
    if(!isset($row[$j])) {
      $schema_insert .= "NULL".$sep;
    }
    elseif ($row[$j] != "") {
      $schema_insert .= "$row[$j]".$sep;
    }
    else {
      $schema_insert .= "".$sep;
    }
  }
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert));
  print "\n";
}
?>





?>