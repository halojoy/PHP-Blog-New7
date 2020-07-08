<?php

class Pagin {

    public $offset;
    public $totpages;

    function __construct()
    {
    }

    function setup($total, $perpage, $pageno)
    {
        $this->offset = ($pageno - 1) * $perpage;
        $this->totpages = ceil($total/$perpage);
    }

    function showfoot()
    {
        if ($this->totpages > 1) {
            echo '<div id="pager">'."\n";
            echo 'Page: ';
            for($i = 1; $i <= $this->totpages; $i++)
                echo '<a href="index.php?p='.$i.'">'.$i.'</a> ';
            echo "\n".'</div>'."\n";
        }
    }
}
