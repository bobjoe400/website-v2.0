<?php  
    //index.php  
    $connect = mysqli_connect('localhost', 'bitch', '42069xd', 'Airplanes');  
    $query = "SELECT * FROM Airplanes ORDER BY line ASC";  
    $result = mysqli_query($connect, $query);
    function error_found(){
        header("Location: index.html");
    }
    set_error_handler('error_found');
?>  
<!DOCTYPE html>  
    <html>  
        <head>  
        <title> Coopers Planes</title>  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>  
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
        </head>  
            <body>  
                <br />            
                <div class="container" align="center">  
                    <h3 class="text-center">These are 161 of the 244 planes that I've worked on</h3>
                    <br />  
                    <div class="table-responsive" id="planes">  
                        <table class="table table-bordered">  
                            <tr>  
                                <th><a class="column_sort" id="line" data-order="desc" href="#">Plane no.</a></th>  
                                <th><a class="column_sort" id="tail" data-order="desc" href="#">tail</a></th>  
                                <th><a class="column_sort" id="type" data-order="desc" href="#">type</a></th>
                                <th><a class="column_sort" id="currapt" data-order="desc" href="#">current apt</a></th>
                                <th><a class="column_sort" id="origin" data-order="desc" href="#">origin</a></th>
                                <th><a class="column_sort" id="destination" data-order="desc" href="#">destination</a></th>
                                <th><a class="column_sort" id="speed" data-order="desc" href="#">speed</a></th>
                                <th><a class="column_sort" id="altitude" data-order="desc" href="#">altitude</a></th>
                                <th><a class="column_sort" id="latitude" data-order="desc" href="#">latitude</a></th>
                                <th><a class="column_sort" id="longitude" data-order="desc" href="#">longitude</a></th>
                                <th>link</th>
                            </tr>  
                            <?php  
                                while($row = mysqli_fetch_array($result))  
                                {  
                                    if($row["tail"]!='NaN'&&$row["ident"]!=null):?>
                                    <tr>  
                                        <td><?php echo  ($row["line"]-815); ?></td>  
                                        <td><?php echo $row["tail"]; ?></td>  
                                        <td><?php echo $row["type"]; ?></td>
                                        <td><?php echo $row["currapt"]; ?></td>
                                        <td><?php echo $row["origin"]; ?></td>
                                        <td><?php echo $row["destination"]; ?></td>
                                        <td><?php echo $row["speed"]; ?></td>
                                        <td><?php echo $row["altitude"]; ?></td>
                                        <td><?php echo $row["latitude"]; ?></td>
                                        <td><?php echo $row["longitude"]; ?></td>
                                        <td><?php echo "<a href='https://flightaware.com/live/flight/".$row["ident"]."'>";?>flight</a></td>
                                    </tr>
                                <?php endif;
                                }  
                            ?>  
                        </table>  
                    </div>  
                </div>  
                <br />  
            </body>  
        </html>  
    <script>  
        $(document).ready(function(){  
            $(document).on('click', '.column_sort', function(){  
                var column_name = $(this).attr("id");  
                var order = $(this).data("order");  
                var arrow = '';  
                //glyphicon glyphicon-arrow-up  
                //glyphicon glyphicon-arrow-down  
                if(order == 'desc')  
                {  
                    arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';  
                }  
                else  
                {  
                    arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';  
                }  
        $.ajax({  
            url:"sort.php",  
            method:"POST",  
                data:{column_name:column_name, order:order},  
                    success:function(data)  
                    {  
                        $('#planes').html(data);  
                        $('#'+column_name+'').append(arrow);  
                    }  
                })  
            });  
        });  
    </script>  