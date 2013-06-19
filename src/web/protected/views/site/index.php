<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<head>
  	<meta charset="utf-8">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Mes', 'Ventas', 'Compras'],
          ['Enero',  1000,      400],
          ['Febrero',  1170,      460],
          ['Marzo',  660,       1120],
          ['Abril',  1030,      540],
          ['Mayo',  1030,      540],
          ['Junio',  1030,      540],
          ['Julio',  1030,      540],
          ['Agosto',  1030,      540],
          ['Septiembre',  1030,      540],
          ['Octubre',  1030,      540],
          ['Nobiembre',  1030,      540],
          ['Diciembre',  1030,      540]
        ]);

        var options = {
          title: 'Rendimiento de la compañia',
          hAxis: {title: 'Años',  titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
<h1>Bienvenido a  <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<div id="chart_div" style="width: 900px; height: 500px;"></div>




<!--<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>-->
<!--	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>-->
