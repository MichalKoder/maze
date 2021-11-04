<?php

class MazeField {
    private $value;
    private $neibrs;
    private $neibrs3;

    public function __construct($value=null) {
        $this->value = $value;
    }

    public function __toString() {
        return $this->value;
    }
}

class MazeGenerator {
    private $width;
    private $height;
    private $board;
    private $boardlen;
    private $startPos;
    private $stack;

    public function __construct($width, $height, $startPos=[0,0]) {
        $this->width = $width;
        $this->height = $height;
        $this->boardlen = $width*$height;
        for($i=0;$i<$this->boardlen; $i++)
            $this->board[] = new MazeField('.');
        $this->startPos = $startPos;
        $this->stack = [];
    }

    public function getField($col=null, $row=null, $index=null) {
        if(!isset($row) && !isset($col) && isset($index)) {
            if (($index < $this->boardlen) && ($index >= 0)) return $this->board[$index];
            return false;
        } else
        if(isset($row) && isset($col) && !isset($index))
         {
             $ind = $this->getIndex($col,$row);
             return $ind ? $this->board[$ind] : false;
         }
         return false;
    }

    public function setField($data, $col = null, $row = null, $index = null) {
        if(isset($index)) $this->board[$index] = $data; 
        else
        if(isset($col) && isset($row)) $this->board[$this->getIndex($col,$row)] = $data;
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

    public function topStack() {
        return end($this->stack);
    }

    public function pushStack($element) {
        $this->stack[] = $element;
    }

    public function popStack() {
        return array_pop($this->stack);
    }

    public function getStartPos() {
        return $this->startPos;
    }

    public function setStartPos($col, $row) {
        $this->startPos = [$col, $row];
    }
}

$maze = new MazeGenerator(10,10,[0,0]);
$maze->pushStack($maze->getStartPos());
$maze->setField('o',$maze->topStack()[0], $maze->topStack()[1]);
$maze->consoleDraw();