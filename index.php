<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maze</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div class="main">
        <div class="mazeframe">
            <div class="mazebody">
                <div class="mazegrid">
                    <!-- generate maze grid cells here with javascript -->
                </div>
            </div>
        </div>
    </div>


<script type="text/javascript">
    $(document).ready(function() {
        var gridcell = '<div class="gridcell"></div>';
        for(let i=0;i<2500;i++) {
        $('.mazegrid').append(gridcell)
        }
    })
</script>
</body>
</html>