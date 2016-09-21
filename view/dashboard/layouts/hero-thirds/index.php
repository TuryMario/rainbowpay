<?php
require_once '../../../conn.php';

//echo ;
mysql_query("select * from ber");
$buyer_check = mysql_query("SELECT * FROM buyer_tb WHERE email = '".$_POST['email']."' ");

if($_POST['email'] == ""){
    echo "<strong><a href='http://ec2-54-191-230-33.us-west-2.compute.amazonaws.com/rainbow/view/dashboard/'>Please Login in Again, Session as Interupted</a> </strong>";
}
$buyer_row = mysql_fetch_assoc($buyer_check);

//echo "Your balance is : ";
//echo $buyer_row['username'];
//echo " </br>";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rainbow</title>
  <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
  <link rel="stylesheet" type="text/css" href="../../assets/lib/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/keen-dashboards.css" />
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script src="https://code.jquery.com/jquery-3.1.0.js" integrity="sha256-slogkvB1K3VOkzAI8QITxV3VzpOnkeNVsKvtkYLMjfk=" crossorigin="anonymous"></script>
     <script src="https://www.gstatic.com/firebasejs/3.0.4/firebase.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
          
          var jsonData = $.ajax({
          url: "http://ec2-54-191-230-33.us-west-2.compute.amazonaws.com/rainbow/view/datatest4.php",
          dataType: "json",
          async: false
          }).responseText;
          
           var jsonDatay = $.ajax({
          url: "http://ec2-54-191-230-33.us-west-2.compute.amazonaws.com/rainbow/view/datatest3.php",
          dataType: "json",
          async: false
          }).responseText;
         // console.log(jsonData);
          var curry  = '"';
          var curryx = "'"
          var datax = jsonData.split(curry).join(curryx);
          var dataxy = jsonData.split(curry).join(curryx);
         // console.log(JSON.parse(jsonData));
           var data = new google.visualization.DataTable();
          var datay = new google.visualization.DataTable();
          // var data = new google.visualization.DataTable(jsonData);
        data.addColumn('string', 'Card No');
        data.addColumn('number', 'Amount');
        //  data.addColumn('number', 'Balance');
           data.addRows(JSON.parse(jsonData));
          
           datay.addColumn('string', 'Card No');
        datay.addColumn('number', 'Amount');
        //  data.addColumn('number', 'Balance');
           datay.addRows(JSON.parse(jsonDatay));
          
         
          //parseInt(datax)
    var options = {
          title: 'Company Performance',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

         
         // var chartx = new google.visualization.LineChart(document.getElementById('chart_div'));
         //  var charty = new google.visualization.AreaChart(document.getElementById('chart_div2'));
           var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
          //BubbleChart
          //AreaChart
          //LineChart
         // Area Chart
        chart.draw(data, options);
           chartx.draw(datay, options);
           charty.draw(data, options);
         
      }
        
        
        
    </script>
</head>
<body class="application">

  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="../../">
          <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="navbar-brand" href="../../">Rainbow Dashboard</a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
          <li><a href="#">Home</a></li>
          <li><a href="#">Support</a></li>
          <li><a href="#">Shopping</a></li>
          <li><a href="#">Technical Support</a></li>
          <button id="buttonx"  onclick="powery()" style="
    margin-top: 3%;
">Sigout</button>
        </ul>
      </div>
    </div>
  </div>

  <div class="container-fluid">

    <div class="row">
      <div class="col-sm-8">
        <div class="chart-wrapper">
          <div class="chart-title">
            Ependiture Pattern
          </div>
          <div class="chart-stage" width="100%">
            <div id="grid-1-1">
              <?php
                
                echo "Welcome to Your Rainbow Dashboard our esteemed customer <strong> Mr/Mrs.".$buyer_row['username']."</strong>";
echo " </br>";
echo "Your balance is : UGX <strong>";
echo $buyer_row['balance'];
echo "</strong> </br>";
                ?>
              <div id="chart_div3"></div> 
                
            </div>
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
      
    </div>

    
      
      
    </div>
  

   

    <p class="small text-muted">Built with &#9829; by <a href="">Rainbow</a></p>

 

  <script type="text/javascript" src="../../assets/lib/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="../../assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="../../assets/lib/holderjs/holder.js"></script>
  <script>
    Holder.add_theme("white", { background:"#fff", foreground:"#a7a7a7", size:10 });
  </script>

  <script type="text/javascript" src="../../assets/lib/keen-js/dist/keen.min.js"></script>
  <script type="text/javascript" src="../../assets/js/meta.js"></script>
<script>
     
     // Initialize Firebase
  var config = {
    apiKey: "AIzaSyCWiOxfjaIxZpRySZ24HKWrS5j9EqZ1p4M",
    authDomain: "rainbow-512e0.firebaseapp.com",
    databaseURL: "https://rainbow-512e0.firebaseio.com",
    storageBucket: "rainbow-512e0.appspot.com",
  };
  firebase.initializeApp(config);
    
    
       window.onload = function(){
      firebase.auth().onAuthStateChanged(function(user) {
  if (user) {
    // User is signed in.
      console.log("User is signed in.");
      
     // window.location = '../dashboard/layouts/hero-thirds/index.php';
  } else {
    // No user is signed in.
      console.log("No user is signed in");
      window.location = '../../';//send person back to dashboard
  }
});
        }
       
      function powery(){
        //observer function
        firebase.auth().signOut().then(function() {
  // Sign-out successful.
            console.log('Sign-out successful.');
             window.location = '../../';//send person back to dashboard
         
           
}, function(error) {
  // An error happened.
            console.log("An error happened.");
});
    }
    </script>
</body>
</html>
