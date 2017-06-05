<?php

interface Custom_Model_AuthInterface
{
    /**
     * 取得认证信息
     *
     * @param    void
     * @return   array
     */
    public function getAuth();
    
    /**
     * 销毁认证信息
     *
     * @param    void
     * @return   void
     */
    public function unsetAuth();
    
    /**
     * 认证
     *
     * @param    string    $username
     * @param    string    $password
     * @param    string    $extra
     * @return   mixed
     */
    public function certification($username, $password, $extra);
}
