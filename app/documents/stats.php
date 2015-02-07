<?php

//script for getting document statistics 

session_start();
include("../includes/header.php");
include("dblayer3.php"); //include my db layer --JR

//debugging
if ($_GET['verbose']) { //to enable debugging messages, add ?verbose=TRUE to the URL, after search.php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<h1>Statistics</h1>

<?php 
$totalDocsQuery = "select count(*) from documents"; 
$totalDocsResult = mysql_query($totalDocsQuery); 

$totalJournalsQuery = "select count(*) from journals"; 
$totalJournalsResult = mysql_query($totalJournalsQuery); 

$datesQuery = "select left(date, 4) d, count(left(date, 4)) c from documents group by d order by d"; 
$datesResult = mysql_query($datesQuery); 

$doctypesQuery = "select type, count(doctype) c from documents, doctypes where documents.doctype=doctypes.id group by doctype"; 
$doctypesResult = mysql_query($doctypesQuery); 

$placesQuery = "select name, count(name) c from mentioned_places group by name having c>0 order by c desc limit 20"; 
$placesResult = mysql_query($placesQuery); 

$peopleQuery = "select name, count(name) c from mentioned_people group by name having c>0 order by c desc limit 20"; 
$peopleResult = mysql_query($peopleQuery); 

$organizationQuery = "select name, count(name) c from mentioned_organizations group by name having c>0 order by c desc limit 20"; 
$organizationResult = mysql_query($organizationQuery); 

$categoryQuery = "select name, count(cat_id) c from test_cat, documents_category where documents_category.cat_id=test_cat.id and parent_id=0 group by cat_id order by c desc limit 20";
$categoryResult = mysql_query($categoryQuery); 

?>

<h2>Numbers of Documents</h2> 

<p>Number of documents: <strong><?php echo mysql_result($totalDocsResult, 0) ?></strong> </p> 
<p>Number of journals where some of these documents are published: <strong><?php echo mysql_result($totalJournalsResult, 0) ?></strong> </p> 

<h2>Top 20 First-Level Categories</h2> 

<script> 
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
     
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Category Name');
      data.addColumn('number', 'Number of Documents');
      data.addRows([
<?php while ($row = mysql_fetch_array($categoryResult)) { 
	extract($row); 
	echo "[\"$name\", $c],";
}  
?> 
      ]);

      // Set chart options
      var options = {'title':'Top 20 First Level Categories',
                     'width':560,
                     'height':560};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.BarChart(document.getElementById('chart_div4'));
      chart.draw(data, options);
    }
    </script>

    <div id="chart_div4" style="width:560; height:300"></div>

<h2>Dates</h2> 

<script> 
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
     
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Date');
      data.addColumn('number', 'Number of Documents');
      data.addRows([
<?php while ($row = mysql_fetch_array($datesResult)) { 
	extract($row); 
	echo "[\"$d\", $c],";
}  
?> 
      ]);

      // Set chart options
      var options = {'title':'Document Dates',
                     'width':560,
                     'height':600};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_div5'));
      chart.draw(data, options);
    }
    </script>

    <div id="chart_div5" style="width:560; height:300"></div>



<h2>Types of Documents in this Archive</h2> 

<script> 
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
     
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Document Type');
      data.addColumn('number', 'Number of Documents');
      data.addRows([
<?php while ($row = mysql_fetch_array($doctypesResult)) { 
	extract($row); 
	echo "[\"$type\", $c],";
}  
?> 
      ]);

      // Set chart options
      var options = {'title':'Document Types',
                     'width':560,
                     'height':600};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.BarChart(document.getElementById('chart_div6'));
      chart.draw(data, options);
    }
    </script>

    <div id="chart_div6" style="width:560; height:300"></div>



<h2>Top 20 Mentioned Places</h2> 

<?php 
//old way just displays the table
//echo "<table>"; 
//while ($row = mysql_fetch_array($placesResult)) { 
//	extract($row); 
//	echo "<tr><td>$name</td><td>$c</td></tr>"; 
//} 
//echo "</table>"; 
?>

<!-- Google Charts Stuff --> 
<!--Load the AJAX API-->
    <script type="text/javascript">
   
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
     
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Place Name');
      data.addColumn('number', 'Number of Mentions');
      data.addRows([
<?php while ($row = mysql_fetch_array($placesResult)) { 
	extract($row); 
	echo "['$name', $c],";
}  
?> 
      ]);

      // Set chart options
      var options = {'title':'Top 20 Mentioned Places',
                     'width':560,
                     'height':560};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>
 
<!--Div that will hold the pie chart-->
    <div id="chart_div" style="width:560; height:300"></div>

<h2>Top 20 Mentioned People</h2> 

<script> 
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
     
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Place Name');
      data.addColumn('number', 'Number of Mentions');
      data.addRows([
<?php while ($row = mysql_fetch_array($peopleResult)) { 
	extract($row); 
	echo "['$name', $c],";
}  
?> 
      ]);

      // Set chart options
      var options = {'title':'Top 20 Mentioned People',
                     'width':560,
                     'height':560};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));
      chart.draw(data, options);
    }
    </script>

    <div id="chart_div2" style="width:560; height:560"></div>

<h2>Top 20 Mentioned Organizations</h2> 

<script> 
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
     
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Organization Name');
      data.addColumn('number', 'Number of Mentions');
      data.addRows([
<?php while ($row = mysql_fetch_array($organizationResult)) { 
	extract($row); 
	echo "[\"$name\", $c],";
}  
?> 
      ]);

      // Set chart options
      var options = {'title':'Top 20 Mentioned Organizations',
                     'width':560,
                     'height':560};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.BarChart(document.getElementById('chart_div3'));
      chart.draw(data, options);
    }
    </script>

    <div id="chart_div3" style="width:560; height:300"></div>


<?php include("../includes/footer.php"); ?>
