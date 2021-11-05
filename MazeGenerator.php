<?php

class MazeField {
    private $value;
    private $type;
    private $neibrs;
    private $neibrs3;

    public function __construct($value=null) {
        $this->value = $value;
        $this->neibrs = [];
        $this->neibrs3 = [];
    }

    public function __toString() {
        return $this->value == null ? '.' : $this->value;
    }

    public function getNeibrs() {
        return $this->neibrs;
    }

    
    public function setNeibrs($arr) {
        $this->neibrs = $arr;
    } 
    
    public function getNeibrs3() {
        return $this->neibrs3;
    }
    
    public function shuffleNeibrs3() { 
        // shuffle in php doesn't maintain keys, so get-around below is needed
        $shuffleKeys = array_keys($this->neibrs3);
        shuffle($shuffleKeys);
        $random = [];
        foreach($shuffleKeys as $key) {
            $random[$key] = $this->neibrs3[$key];
        }
        return $random;
    }

    public function setNeibrs3($arr) {
        $this->neibrs3 = $arr;
    } 

    public function reset() {
        $this->neibrs3 = $this->neibrs;
    }

    public function addNeibr($coords) {
        // $this->neibrs[] = $coords;
        $this->neibrs = array_merge($this->neibrs, $coords);
    }

    public function dropNeibr($key) {
        unset($this->neibrs3[$key]);
    }

    public function getType($type) {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($val) {
        $this->value = $val;
    }

}

class MazeGenerator {
    private $width;
    private $height;
    private $board;
    private $boardlen;
    private $headPos;
    private $trace; // stack

    public function __construct($width, $height, $headPos=[0,0]) {
        $this->width = $width;
        $this->height = $height;
        $this->boardlen = $width*$height;
        for($i=0;$i<$this->boardlen; $i++)
            $this->board[] = new MazeField(null);
        $this->headPos = $headPos;
        $this->trace = [];

    }

    public function initFieldNeibrs($col, $row) {
        if(!isset($row) || !isset($col)) return false; 
        $ind = $this->getIndex($col,$row);

        if($row > 0) $this->board[$ind]->addNeibr(['north'=>[$col,$row-1]]);
        if($col < $this->width-1) $this->board[$ind]->addNeibr(['east'=>[$col+1,$row]]);
        if($row < $this->height-1) $this->board[$ind]->addNeibr(['south'=>[$col,$row+1]]);
        if($col > 0) $this->board[$ind]->addNeibr(['west'=>[$col-1,$row]]);

        $this->board[$ind]->reset(); // set free neigbours list same as init neigbours
    }

    public function initFieldsNeibrs() {
        for($j=0;$j<$this->height;$j++) {
            for($i=0;$i<$this->width;$i++) {
                $this->initFieldNeibrs($i,$j);
            }
        }
    }


    public function getField($col=null, $row=null, $index=null) {
        if(!isset($row) && !isset($col) && isset($index)) {
            if (($index < $this->boardlen) && ($index >= 0)) return $this->board[$index];
            return false;
        } else
        if(isset($row) && isset($col) && !isset($index))
         {
             $ind = $this->getIndex($col,$row);
             return $ind || ($ind === 0) ? $this->board[$ind] : false;
         }
         return false;
    }

    public function setFieldValue($data, $col = null, $row = null, $index = null) {
        if(isset($index)) $this->board[$index]->setValue = $data; 
        else
        if(isset($col) && isset($row)) $this->board[$this->getIndex($col,$row)]->setValue($data);
        else return false;
    }

    public function setFieldType($type, $col = null, $row = null, $index = null) {
        if(isset($index)) $this->board[$index]->setType = $type;
        else
        if(isset($col) && isset($row)) $this->board[$this->getIndex($col,$row)]->setType($type);
        else return false;
    }

    public function getCoords($index) {
        return $index > $this->boardlen ? false : [$index % $this->width, (int)($index / $this->width)];
    }

    public function getIndex($col,$row) {
        if ($col >= $this->width || $col < 0 || $row >= $this->height || $row < 0) return false;
        return $row*$this->width + $col;
    }

    public function consoleDraw() {
        echo "\n".str_repeat('#  ',$this->width+2)."\n";
        for($j=0;$j<$this->height;$j++) {
            echo '#';
        for($i=0;$i<$this->width;$i++) {
            echo '  '.$this->board[$this->getIndex($i,$j)];
        }
        echo '  #'."\n";
    }
    echo str_repeat('#  ',$this->width+2)."\n";
    }

    public function topTrace() {
        return end($this->trace);
    }

    public function pushTrace($element) {
        $this->trace[] = $element;
    }

    public function popTrace() {
        return array_pop($this->trace);
    }

    public function getHeadPos() {
        return $this->headPos;
    }

    public function setHeadPos($col, $row) {
        $this->headPos = [$col, $row];
    }

    public function getHead() {
        return $this->getField($this->getHeadPos()[0],$this->getHeadPos()[1]);
    }

    public function updateField($col,$row) {

        // $this->board[$this->getIndex($col,$row)]->setNeibrs(something);
    }


    public function goNorth() {
        if($this->headPos[1] == 0) return false; // going north is impossible due to the north wall collision
        $this->headPos[1]--;
        // $this->getField($this->headPos[0],$this->headPos[1])->dropNeibr('south');
        $this->informNeibrs();
        $this->setFieldType($this->headPos[0],$this->headPos[1], 'path');
    }

    public function goSouth() {

        if($this->headPos[1] == $this->height-1) return false; // check border touch
        $this->headPos[1]++;
        $this->informNeibrs();
        // $this->getField($this->headPos[0],$this->headPos[1])->dropNeibr('north');
        $this->setFieldType($this->headPos[0],$this->headPos[1], 'path');
    }

    public function goEast() {

        if($this->headPos[0] == $this->width-1) return false; // check border touch
        $this->headPos[0]++;
        $this->informNeibrs();

        // $this->getField($this->headPos[0],$this->headPos[1])->dropNeibr('west');
        $this->setFieldType($this->headPos[0],$this->headPos[1], 'path');
    }

    public function goWest() {
        if($this->headPos[0] == 0) return false; // check border touch
        $this->headPos[0]--;
        $this->informNeibrs();

        // $this->getField($this->headPos[0],$this->headPos[1])->dropNeibr('east');
        $this->setFieldType($this->headPos[0],$this->headPos[1], 'path');
    }

    public function getOppDir($dir) {
        $l = ['east' => 'west', 'west' => 'east', 'north' => 'south', 'south' => 'north'];
        return $l[$dir];
    }

    public function informNeibrs() {
        $neibrs = $this->getHead()->getNeibrs3(); // get head's neighbours list
        foreach($neibrs as $dir=>$n) {
            $oppositeDir = $this->getOppDir($dir);
            $this->getField($n[0],$n[1])->dropNeibr($oppositeDir);
        }

    }
}

$maze = new MazeGenerator(width:6,height:6,headPos:[0,0]);
// Ustaw wszystkich sąsiadów
$maze->initFieldsNeibrs();
// Poinformuj siąsiadów, że nie jesteś już ich sąsiadem 
// ( a przynajmniej nie takim na którego można nadepnąć ;))
$maze->informNeibrs();

// var_dump($maze->getField(col:0,row:0));
for($i=0;$i<10;$i++) {
$maze->pushTrace($maze->getHeadPos());
$maze->setFieldValue('o',$maze->topTrace()[0], $maze->topTrace()[1]);

$possibleMoves = $maze->getHead()->shuffleNeibrs3();
if(empty($possibleMoves)) {
    // backtracking
    do {
        $maze->setHeadPos($maze->popTrace()[0], $maze->popTrace()[1]);
        $possibleMoves = $maze->getHead()->shuffleNeibrs3();
    }
    while (empty($possibleMoves));
}

$chosenMove = array_keys($possibleMoves)[0];
// var_dump($chosenMove);

switch($chosenMove) {
    case 'east' : $maze->goEast();break; 
    case 'west' : $maze->goWest();break; 
    case 'north' : $maze->goNorth();break; 
    case 'south' : $maze->goSouth();break; 
}

// var_dump($maze->getHead()->getNeibrs());
// var_dump($maze->getHead()->shuffleNeibrs3());
// $ticketDraw = rand()%4;
// switch($ticketDraw) {
//     case 0 : $maze->goEast();break; 
//     case 1 : $maze->goWest();break; 
//     case 2 : $maze->goNorth();break; 
//     case 3 : $maze->goSouth();break; 
// }

}
$maze->consoleDraw();
