<?php
$startPos = [0,0];
$bitmap = [];
 if(isset($_POST['generate'])) {
 $bitmap[] = $startPos;
 $limits = 0;
 /*
    Rząd 0 : nie moze na polnoc
    Rząd ostatni: nie może na poludnie
    kolumna 0: nie może na zachod
    kolumna ostatnia: nie moze na wschod
 */

 // here generate data about maze walls position
 echo rand()%5; // placeholder
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maze</title>
    <link rel="stylesheet" href="styles.css">
    <script src="./jquery.min.js"></script>
</head>
<body>
    <div class="main">
        <div class="mazeframe">
            <form action="" method="post">
            <input type="submit" id="generate" name="generate" value="fetch maze"></input>
            </form> 
            <div class="mazebody">
                <div class="mazegrid">
                    <!-- generate maze grid cells here with javascript -->
                </div>
            </div>
        </div>
    </div>


<script type="text/javascript">
    $(document).ready(function() {
        var gridcell = ''; 
        var bitmap = [];
        var col =0, row = 0;
        for(let i=0; i<25; i++) {
        col = (i%5);
        row = parseInt(i/5);
        gridcell = '<div class="gridcell r'+row+' c'+col+'" id=r'+row+'-c'+col+'></div>';
        $('.mazegrid').append(gridcell);
        }
        <?php
        for($i=0; $i<count($bitmap); $i++) {
        echo 'bitmap.push(['.$bitmap[$i][0].','.$bitmap[$i][1].']);';
        }?>
        console.log(bitmap);
        $('#r'+bitmap[0][0]+'-c'+bitmap[0][1]).css('background-color', 'darkslategrey');
    })
</script>
</body>
</html>