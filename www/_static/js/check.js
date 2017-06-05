/**
 * 验证类
 * 
 * @return void
 */
var Check = new Object();

/**
 * 验证是否为数字
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isNumber = function(val) {
	return /^[\d|\.|,]+$/.test(val);
}

/**
 * 验证是否为整数
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isInt = function(val) {
	return /^\d+$/.test(val);
}

/**
 * 验证是否为电子邮件
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isEmail = function(val) {
	return /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/
			.test(val);
}

/**
 * 验证是否为手机
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isMobile = function(val) {
	return /^1[34568]+\d{9}$/.test(val);
}

/**
 * 验证是否为电话
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isTel = function(val) {
	return /^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/.test(val);
}

/**
 * 验证是否为传真
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isFax = function(val) {
	return /^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/.test(val);
}

/**
 * 验证是否为邮政编码
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isZip = function(val) {
	return /^\d{6}$/.test(val);
}

/**
 * 验证是否为QQ
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isQq = function(val) {
	return /^\d{4,}$/.test(val);
}

/**
 * 验证是否为有效的HTTP协议地址
 * 
 * @param string
 *            val
 * @return bool
 */
Check.isHTTP = function(val) {
	return /^(http:\/\/)?[-A-Za-z0-9]+\.[-A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/
			.test(val);
}