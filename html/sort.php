 <?php  
 //sort.php  
 $connect = mysqli_connect("localhost", "bitch", "42069xd", "Airplanes");  
 $output = '';  
 $order = $_POST["order"];  
 if($order == 'desc')  
 {  
      $order = 'asc';  
 }  
 else  
 {  
      $order = 'desc';  
 }  
 $query = "SELECT * FROM Airplanes ORDER BY ".$_POST["column_name"]." ".$_POST["order"]."";  
 $result = mysqli_query($connect, $query);  
 $output .= '  
 <table class="table table-bordered">  
      <tr>  
            <th><a class="column_sort" id="line" data-order="'.$order.'"  href="#">Plane no.</a></th>  
            <th><a class="column_sort" id="tail" data-order="'.$order.'" href="#">tail</a></th>  
            <th><a class="column_sort" id="type" data-order="'.$order.'" href="#">type</a></th>
            <th><a class="column_sort" id="currapt" data-order="'.$order.'" href="#">current apt</a></th>
            <th><a class="column_sort" id="origin" data-order="'.$order.'" href="#">origin</a></th>
            <th><a class="column_sort" id="destination" data-order="'.$order.'" href="#">destination</a></th>
            <th><a class="column_sort" id="speed" data-order="'.$order.'" href="#">speed</a></th>
            <th><a class="column_sort" id="altitude" data-order="'.$order.'" href="#">altitude</a></th>
            <th><a class="column_sort" id="latitude" data-order="'.$order.'" href="#">latitude</a></th>
            <th><a class="column_sort" id="longitude" data-order="'.$order.'" href="#">longitude</a></th>
            <th>link</th>
      </tr>  
 ';  
 while($row = mysqli_fetch_array($result))  
 {
    if($row["tail"]!='NaN'&&$row["ident"]!=null):
      $output .= '  
      <tr>  
           <td>' . ($row["line"]-815) . '</td>  
           <td>' . $row["tail"] . '</td>  
           <td>' . $row["type"] . '</td>
           <td>' . $row["currapt"] . '</td> 
           <td>' . $row["origin"] . '</td> 
           <td>' . $row["destination"] . '</td> 
           <td>' . $row["speed"] . '</td> 
           <td>' . $row["altitude"] . '</td>
           <td>' . $row["latitude"] . '</td> 
           <td>' . $row["longitude"] . '</td> 
           <td><a href="https://flightaware.com/live/flight/'.$row["ident"].'">flight</a></td> 
      </tr>  
      ';  
     endif;
 }  
 $output .= '</table>';  
 echo $output;  
 ?>  