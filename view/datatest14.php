<?php 
require_once 'conn.php';
//header('Allow-Control-Allow-Origin:*');//allowing
header('Access-Control-Allow-Origin: *');
//external resource access
/****************************/
/** STRICTLY RESEARCH WORK **/
/*****************************/

//$sql_data  = mysql_query("select * from transact_tb where type = 'purchase' AND card_no = '2016' limit 200");failed_purchase
//$sql_data  = mysql_query("select * from purchase_tb  limit 100,300");
//pending_tb
$sql_data  = mysql_query("select * from payments_tb");

$numrows = mysql_num_rows($sql_data);

//echo  $numrows;
//$arr =  array('Type', 'Sales');
$month_array =  array();
 $amount_array =  array();
//echo '[';
//echo json_encode($arr).",";
$counter = 0;
 while ($row_pending = mysql_fetch_assoc($sql_data) AND $counter <300)
{
          json_encode(array(substr($row_pending['time_stamp'], 5, -12),(int)$row_pending['amount'])).",";
     $counter++;
     array_push($month_array,substr($row_pending['time_stamp'],  5, -12));
  array_push($amount_array,(int)$row_pending['amount']);
     }
$arrx =  array('2016-05-12',0);
//echo json_encode($arrx);

//echo ']';

//echo "******************</br>";
echo '[';
echo json_encode($arr).",";
//echo "******************</br>";
//echo "******************</br>";
//create the arrays and populate them

//echo json_encode($month_array);//date array
//echo json_encode($amount_array);//amount array

//get unique members of month array
$unique_month_array =  $month_array;

$unique_month_array =  array_values(array_unique($unique_month_array));

//create totla holder
$total_monthly_amount = 0;

// create array to hold totals
$total_monthly_totals_arrays = array();

//create to watch the months being worked on 
$monthly_count = array();

for($i=0;$i<count($unique_month_array);$i++){
    
     //re initialize the total to zero
    $total_monthly_amount = 0;
    
        for($z=0;$z<count($month_array);$z++){
        if($unique_month_array[$i] == $month_array[$z] ){
          $total_monthly_amount += $amount_array[$z];  
        }
    }
    
    //add new total to array
    array_push($total_monthly_totals_arrays,$total_monthly_amount);
    
    //add to month watch count
    array_push($monthly_count,$unique_month_array[$i]);
 
echo json_encode(array($unique_month_array[$i],$total_monthly_amount)).",";
}

$arrx =  array('XX',0);
echo json_encode($arrx);
echo ']';
/*
echo "******************</br>";
echo "******************</br>";
echo "******************</br>";
echo "******Unique months Test array*********</br>";
echo  json_encode($unique_month_array);
echo "******Unique months array*********</br>";
echo  json_encode(array_unique($month_array)[1]);
echo "******Unique months count*********</br>";
echo json_encode($monthly_count);
echo "******Unique monthly Totals*********</br>";
echo json_encode($total_monthly_totals_arrays);
*/
?>