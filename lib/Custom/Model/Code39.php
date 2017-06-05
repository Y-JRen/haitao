<?php
/***********************************************************************
显示code39条形码的类
使用方法：
$convert=new code39;
$str=$convert->decode($str);
***********************************************************************/

class pattenclass
{
    var $color;
    var $width;
    function pattenclass($color,$width)
    {
        $this->color=$color;
        $this->width=$width;
    }
}

class code39
{
    public $zoom;
    public $height;
    public $show;
    public $patten=array();
    
    function __construct($zoom=1.3, $height=25, $show=true)
    {
        $this->zoom=$zoom<1 || $zoom>20 ? 1 : $zoom;
        $height=intval($height);
        $this->height=$height<1||$height>80?30:$height;
        $this->show=$show;
        $this->patten[]=new pattenclass("#FFFFFF",1*$this->zoom);
        $this->patten[]=new pattenclass("#FFFFFF",3*$this->zoom);
        $this->patten[]=new pattenclass("#000000",1*$this->zoom);
        $this->patten[]=new pattenclass("#000000",3*$this->zoom);
    }
    
    function makecode($code)//code39解码
    {
        switch ($code)
        {
            case "0":return ("202130302");
            case "1":return ("302120203");
            case "2":return ("203120203");
            case "3":return ("303120202");
            case "4":return ("202130203");
            case "5":return ("302130202");
            case "6":return ("203130202");
            case "7":return ("202120303");
            case "8":return ("302120302");
            case "9":return ("203120302");
            case "A":return ("302021203");
            case "B":return ("203021203");
            case "C":return ("303021202");
            case "D":return ("202031203");
            case "E":return ("302031202");
            case "F":return ("203031202");
            case "G":return ("202021303");
            case "H":return ("302021302");
            case "I":return ("203021300");
            case "J":return ("202031302");
            case "K":return ("302020213");
            case "L":return ("203020213");
            case "M":return ("303020212");
            case "N":return ("202030213");
            case "O":return ("302030212");
            case "P":return ("203030212");
            case "Q":return ("202020313");
            case "R":return ("302020312");
            case "S":return ("203020312");
            case "T":return ("202030312");
            case "U":return ("312020203");
            case "V":return ("213020203");
            case "W":return ("313020202");
            case "X":return ("212030203");
            case "Y":return ("312030202");
            case "Z":return ("213030202");
            case "-":return ("212020303");
            case ".":return ("312020302");
            case " ":return ("213020302");
            case "*":return ("212030302");
            case "$":return ("212121202");
            case "/":return ("212120212");
            case "+":return ("212021212");
            case "%":return ("202121212");
        }
        return ("212030302");
    }
    
    function display($code)//输出单个字符
    {
        $output="";
        for ($i=0;$i<9;$i++)
            $output.="<td height=".$this->height." bgcolor=".$this->patten[$code[$i]]->color." width=".$this->patten[$code[$i]]->width."></td>";
        return $output;
    }
    
    function decode($code)//全部输出
    {
        $length=strlen($code);
        $tbw = $this->height/2*($length+2)*$this->zoom - ($length*3);
        $tdw = $tbw/($length+2);
        $output="<table cellspacing=\"0\" cellpadding=\"0\" align=\"center\" ><tr><td><table width=".($this->height/2*(strlen($code))*$this->zoom)." height=".$this->height." border=0 cellspacing=0 cellpadding=0 align=\"center\"><tr>";
        $output.=$this->display($this->makecode("*"));
        $output.="<td height=".$this->height." bgcolor=".$this->patten[0]->color." width=".$this->patten[0]->width."></td>";
        for ($i=0;$i<$length;$i++)
        {
            $output.=$this->display($this->makecode($code[$i]));
            $output.="<td height=".$this->height." bgcolor=".$this->patten[0]->color." width=".$this->patten[0]->width."></td>";
        }
        $output.=$this->display($this->makecode("*"));
        if($this->show) {
        $output.="</td></tr></table></td></tr><tr><td height=\"14\"><table width=\"100%\" bgcolor=\"#ffffff\" height=14 border=0 cellspacing=0 cellpadding=0 align=\"center\"><tr>";
        $length=strlen($code);
        $output.="<td align=\"center\">*</td>";
        for ($i=0;$i<$length;$i++)
        {
            $output.="<td align=\"center\">".$code[$i]."</td>";
        }
        $output.="<td align=\"center\">*</td>";
        $output.="</tr></table>";
        }
        $output.="</td></tr></table>";
        
        return $output;
    }
}

