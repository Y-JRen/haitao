<?php
/**
 * @see PHPMailer
 */
require_once 'Custom/Model/Mail/class.phpmailer.php';

class Custom_Model_Mail
{
	/**
     * phpmailer类
     * 
     * @var PHPMailer
     */
	private $_mail = null;
	
    private $domsec;

	/**
     * 对象初始化
     * 
     * @param    void
     * @return   void
     */
    public function __construct()
    {
    	$this -> _shopConfig = Zend_Registry::get('shopConfig');
    	$this -> _mail = new PHPMailer();
    	$this -> _mail -> Host = $this -> _shopConfig['email_smtp_address'];
    	$this -> _mail -> Port = $this -> _shopConfig['email_smtp_port'];
    	$this -> _mail -> CharSet = $this -> _shopConfig['email_encode'];
    	$this -> _mail -> Sender = $this -> _shopConfig['email_smtp_username'];
        $this ->domsec = '国药集团1健康商城';
    }
    
    /**
     * 发送邮件
     * 
     * @param    $from
     * @param    $fromname
     * @param    $to
     * @param    $toname
     * @param    $subject
     * @param    $content
     * @return   void
     */
    public function send($to, $subject, $content, $from = null, $fromname = null)
    {
    	$subject = ($this -> _shopConfig['email_encode'] && $this -> _shopConfig['email_encode'] != 'UTF-8') ? mb_convert_encoding($subject, $this -> _shopConfig['email_encode'], 'UTF-8') : '';
    	$content = ($this -> _shopConfig['email_encode'] && $this -> _shopConfig['email_encode'] != 'UTF-8') ? mb_convert_encoding($content, $this -> _shopConfig['email_encode'], 'UTF-8') : '';
    	($this -> _shopConfig['email_service_type'] == 1) && $this -> setSMTP();
    	$from = ($from) ? $from : $this -> _shopConfig['email_smtp_username'];
    	$this -> _shopConfig['email_pop_username'] && $this -> _mail -> AddReplyTo($this -> _shopConfig['email_pop_username']);
    	$this -> _mail -> From = $from;
    	$this -> _mail -> FromName = ($fromname) ? mb_convert_encoding($fromname, $this -> _shopConfig['email_encode'], 'UTF-8')  : mb_convert_encoding($this ->domsec, $this -> _shopConfig['email_encode'], 'UTF-8');
    	$this -> _mail -> Subject = $subject;
    	$this -> _mail -> MsgHTML($content);
    	$this -> _mail -> AddAddress($to);
    	$this -> _mail -> IsHTML(true);
    	if ($this -> _mail -> Send() != true) {
    	    return '发送邮件失败!(Mailer Error: ' . $this -> _mail -> ErrorInfo . ')';
    	}
    }
    
    /**
     * 前台商品的推荐给朋友
     * 
     * @param    $from
     * @param    $fromname
     * @param    $to
     * @param    $toname
     * @param    $subject
     * @param    $content
     * @return   void
     */
    public function sendToFriend($to, $subject, $content, $from = null, $fromname = null)
    {
    	$subject = ($this -> _shopConfig['email_encode'] && $this -> _shopConfig['email_encode'] != 'UTF-8') ? mb_convert_encoding($subject, $this -> _shopConfig['email_encode'], 'UTF-8') : '';
    	$content = ($this -> _shopConfig['email_encode'] && $this -> _shopConfig['email_encode'] != 'UTF-8') ? mb_convert_encoding($content, $this -> _shopConfig['email_encode'], 'UTF-8') : '';
    	($this -> _shopConfig['email_service_type'] == 1) && $this -> setSMTP();
    	$from = ($from) ? $from : $this -> _shopConfig['email_smtp_username'];
    	$this -> _shopConfig['email_pop_username'] && $this -> _mail -> AddReplyTo($from);
    	$this -> _mail -> From = $from;
    	$this -> _mail -> FromName = ($fromname) ? mb_convert_encoding($fromname, $this -> _shopConfig['email_encode'], 'UTF-8')  : mb_convert_encoding($this ->domsec, $this -> _shopConfig['email_encode'], 'UTF-8');
    	$this -> _mail -> Subject = $subject;
    	$this -> _mail -> MsgHTML($content);
    	$this -> _mail -> AddAddress($to);
    	$this -> _mail -> IsHTML(true);
    	
    	if ($this -> _mail -> Send() != true) {
    	    return '发送邮件失败!(Mailer Error: ' . $this -> _mail -> ErrorInfo . ')';
    	}
    }
    /**
     * 设置SMTP参数
     * 
     * @param    void
     * @return   void
     */
    private function setSMTP()
    {
    	$this -> _mail -> IsSMTP();
    	$this -> _mail -> SMTPAuth = true;
    	//$this -> _mail -> SMTPSecure = "ssl";
    	$this -> _mail -> Username = $this -> _shopConfig['email_smtp_username'];
    	$this -> _mail -> Password = $this -> _shopConfig['email_smtp_password'];
    }
}