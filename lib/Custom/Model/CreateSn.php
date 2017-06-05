<?php
class Custom_Model_CreateSn
{
    /**
     * 创建SN
     *
     * @return   void
     */
    public static function createSn($str='H')
    {
        $sn = $str.date('ymdHis', time()).mt_rand(10,99);
        return $sn;
    }
}