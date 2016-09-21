<?php
require_once '../../../conn.php';

$buyer_check = mysql_query("SELECT * FROM buyer_tb WHERE username = 'bank' && card_no = '2016' ");
$buyer_row = mysql_fetch_assoc($buyer_check);

echo "Welcome to Your Rainbow Dashboard our esteemed customer <strong> Mr/Mrs.".$buyer_row['username']."</strong>";
echo " </br>";
echo "Your balance is : ";
echo $buyer_row['balance'];
echo " </br>";

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

         
          var chartx = new google.visualization.LineChart(document.getElementById('chart_div'));
           var charty = new google.visualization.AreaChart(document.getElementById('chart_div2'));
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
        <a class="navbar-brand" href="../">
          <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="navbar-brand" href="./">Rainbow Dashboard</a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
          <li><a href="#">Home</a></li>
          <li><a href="#">Team</a></li>
          <li><a href="#">Source</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">Technical Support</a></li>
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
          <div class="chart-stage">
            <div id="grid-1-1">
              
              <div id="chart_div3"></div> 
                
            </div>
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            Deposit Pattern
          </div>
          <div class="chart-stage">
            <img data-src="holder.js/100%x240/white">
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            Failed Transactions
          </div>
          <div class="chart-stage">
            <img data-src="holder.js/100%x120/white">
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            Purchase Distribution
          </div>
          <div class="chart-stage">
            <div id="chart_div2"></div> 
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            Expectated Expenditure
          </div>
          <div class="chart-stage">
            <img data-src="holder.js/100%x120/white">
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
<!-- end of three -->
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            Cell Title
          </div>
          <div class="chart-stage">
           <div id="chart_div"></div> 
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            Cell Title
          </div>
          <div class="chart-stage">
            <img data-src="holder.js/100%x120/white">
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            Cell Title
          </div>
          <div class="chart-stage">
            <img data-src="holder.js/100%x120/white">
          </div>
          <div class="chart-notes">
            Notes about this chart
          </div>
        </div>
      </div>
    </div>
  </div>


    <hr>

    <p class="small text-muted">Built with &#9829; by <a href="https://keen.io">Keen IO</a></p>

  </div>

  <script type="text/javascript" src="../../assets/lib/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="../../assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="../../assets/lib/holderjs/holder.js"></script>
  <script>
    Holder.add_theme("white", { background:"#fff", foreground:"#a7a7a7", size:10 });
  </script>

  <script type="text/javascript" src="../../assets/lib/keen-js/dist/keen.min.js"></script>
  <script type="text/javascript" src="../../assets/js/meta.js"></script>

</body>
</html>
