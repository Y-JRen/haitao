<?php
/***********************************************************************
条形码类
***********************************************************************/
class Custom_Model_Barcode
{
    public $fontSize;
    public $barHeight;
    public $stretchText;
    function __construct($fontSize=15, $barHeight=35, $stretchText=true)
    {
        $this->fontSize=$fontSize;
        $this->barHeight=$barHeight;
        $this->stretchText=$stretchText;
    }
    function makecode($code)
    {
        $code = str_replace(array('_'), array('ZZZZ'), $code);
        
        $barcodeOptions = array('text' => $code , 'barHeight' => $this->barHeight , 'fontSize' => $this->fontSize , 'factor' => 1, 'stretchText' => $this->stretchText);  
        $rendererOptions = array('imageType' => 'gif');
        Zend_Barcode::factory(
            'code39', 'image', $barcodeOptions, $rendererOptions
        )->render();
        exit;
    }
}



     