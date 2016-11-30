<?php
namespace extensions;
 echo "aabb";

 $this->Widget('ext.highcharts.HighchartsWidget', array(
   'options'=>array(
      'title' => array('text' => 'Fruit Consumption'),
      'xAxis' => array(
         'categories' => array('Apples', 'Bananas', 'Oranges')
      ),
      'yAxis' => array(
         'title' => array('text' => 'Fruit eaten')
      ),
      'series' => array(
         array('name' => 'Jane', 'data' => array(1, 0, 4)),
         array('name' => 'John', 'data' => array(5, 7, 3))
      )
   )
  ));
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <script type="text/javascript" src="node_module/jquery-1.8.3.min.js"></script>
 <!-- <script type="text/javascript" src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
 -->
 <script type="text/javascript" src="node_module/highcharts.js"></script>
 <!-- <script type="text/javascript" src="node_modules/exporting.js"></script>
  -->
</head>
<body>
 <div id="container" style="min-width:300px;height:300px;"></div>
 11334ff
 <script>
  $(function(){
  $('#container').highcharts({
    //     chart:{
    //      type:'column'
    //     },
 
      title:{
          text:'Monthly Average Templerature',
          x:-20
      },
      subtitle:{
          text:'Source  worldclimate.com',
          x:-20
      },
      xAxis:{
          categories:['Jan','Feb','Mar','Apr','May','Jun',
              'Jul','Aug','Sep','Oct','Nov','Dec']
      },
      yAxis:{
          title:{
           text:'Temperature(@C)'
          },
          plotline:[{
             value:2,
             width:2,
             color:'#808080' 
          }]
      },
      tooltip:{
           valueSuffix:'@CC'
       },
       legend:{
           layout:'vertical',
           align:'right',
           verticalAlign:'middle',
           borderWidth:0
      },
      series:[{
          name:'tokyo',
          data:[7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
          },{
          name:'纽约',
          data:[-0.2, 0.8,5.7 , 11.3, 17.0, 22.0 , 24.8,24.1, 20.1, 14.1, 8.6, 2.5]
           }]
         });
      });
 </script>";
</body>
</html>
