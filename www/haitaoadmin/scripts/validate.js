
/**************************************************************

	Script		: Validate
	Version		: 2.5  mootools 1.2

**************************************************************/

var Validate = new Class({
	
	options: {
			validateOnBlur: true,
			errorClass: 'error',
			errorMsgClass: 'errorMessage',
			onFailure: Class.empty,
			onSuccess: false,
			showErrorsInline: true,
			submitType: 0,
			submitTarget: 'ifrmSubmit',
			label: '提交中...'
	},

	initialize: function(form, options){
		this.setOptions(options);
		
		this.form = $(form);
		this.elements = this.form.getElements('.required');
		
		this.list = [];
		
		this.elements.each(function(el,i){
			if(this.options.validateOnBlur){
				el.addEvent('blur', this.validate.bind(this, el));
			}
		}.bind(this));
		
		this.form.addEvent('submit', function(e){
			var doSubmit = true;
			this.elements.each(function(el,i){
				if(!this.validate(el)){
					e.stop();
					doSubmit = false;
					this.list.include(el);
				}else{
					this.list.erase(el);
				}
			}.bind(this));
			
			if(doSubmit){
				if(this.options.onSuccess){
					e.stop();
					this.options.onSuccess(this.form);
				}else{
					if(this.options.submitType==2){
					    this.form.target = this.options.submitTarget;
					}else if(this.options.submitType==1){
						e.stop();
					    this.form.set('send', {
					        evalScripts: true,
					        onRequest: function()
					        {
					            loading();
					        },
					        onSuccess: function(data)
					        {
					            loadSucess();
					        },
					        onFailure: function()
					        {
					            alert('error');
					        }
					    }).send();
					} else {
					    this.form.getElement('input[type=submit]').setProperty('value',this.options.label);
					}
				}
			}else{
				alert('请按照提示正确填写表单！');
				this.onFailure();
			}
			
		}.bind(this));
		
	},
	
	onFailure: function(){
		var list = new Element('ul');
		this.list.each(function(el,i){
			if(el.getAttribute("msg") != ''){
			var li = new Element('li').injectInside(list);
			new Element('label').setProperty('for', el.id).set('text',el.getAttribute("msg")).injectInside(li);
			}
		});
		return list;
	},
	
	validate: function(el){
		var valid = true;
		this.clearMsg(el);
		
		switch(el.type){
			case 'text':
			case 'password':
			case 'file':
			case 'textarea':
			case 'select-one':
				if(el.value != ''){
					if(el.hasClass('email')){
						var regEmail = /^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/;
						if(el.value.toUpperCase().match(regEmail)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入正确Email');
						}
					}
					
					if(el.hasClass('number')){
						var regNum = /[-+]?[0-9]*\.?[0-9]+/;
						if(el.value.match(regNum)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效数字');
						}
					}
					
					if(el.hasClass('phone')){
						var regNum = /^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/;
						if(el.value.match(regNum)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效电话号码');
						}
					}
					
					if(el.hasClass('mobile')){
						var regNum = /^((\(\d{3}\))|(\d{3}\-))?13\d{9}$/;
						if(el.value.match(regNum)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效手机');
						}
					}
					
					if(el.hasClass('url')){
						var regNum = /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/;
						if(el.value.match(regNum)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效URL');
						}
					}
					
					if(el.hasClass('idcard')){
						var regNum = /^\d{15}(\d{2}[A-Za-z0-9])?$/;
						if(el.value.match(regNum)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效身份证');
						}
					}
					
					if(el.hasClass('zip')){
						var regPC = /^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) [0-9][A-Za-z]{2})$/
						if(el.value.match(regPC)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效邮编');
						}
					}
					
					if(el.hasClass('qq')){
						var regNum = /^[1-9]\d{4,8}$/;
						if(el.value.match(regNum)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效QQ');
						}
					}
					
					if(el.hasClass('int')){
						var regNum = /^[-\+]?\d+$/;
						if(el.value.match(regNum)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入整数');
						}
					}
					
					if(el.hasClass('unsafe')){
						var regNum = /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/;
						if(el.value.match(regNum)){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '包含非法字符');
						}
					}
					
					if(el.hasClass('date')){
						var d = this.isDate(el.value);
						if(d){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效日期格式: YYYY-mm-dd');
						}
					}
					
					if(el.hasClass('datetime')){
						var d = this.isDateTime(el.value);
						if(d){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效日期时间格式: YYYY-mm-dd HH:ii:ss');
						}
					}
					
					if(el.hasClass('time')){
						var d = this.isTime(el.value);
						if(d){
							valid = true;
						}else{
							valid = false;
							this.setMsg(el, '请输入有效时间格式: HH:ii:ss');
						}
					}
					
			        if(el.hasClass('limit')){
						var min = el.getAttribute('min').trim().toInt();
					    var max = el.getAttribute('max').trim().toInt();
						var t = el.value.trim().toInt();
						if(t < min || t > max){
							valid = false;
							this.setMsg(el,'请输入数字'+min+'-'+max);
						}else{
							valid = true;
						}
			        }
					
			        if(el.hasClass('limitlen')){
						var min = el.getAttribute('min').trim().toInt();
					    var max = el.getAttribute('max').trim().toInt();
						var t = el.value.trim().length;
						if(t < min || t > max){
							valid = false;
							this.setMsg(el,'请输入'+min+'-'+max+'个字符');
						}else{
							valid = true;
						}
			        }
					
			        if(el.hasClass('equal')){
						var to = el.getAttribute('to');
						if(el.value != this.form.getElement('input[name=' + to + ']').value){
							valid = false;
							this.setMsg(el,'输入不一致');
						}else{
							valid = true;
						}
			        }
					
				}else{
					valid = false;
					this.setMsg(el);
				}
				break;
				
			case 'checkbox':
				if(!el.checked){
					valid = false;
					this.setMsg(el);
				}else{
					valid = true;
				}
				break;
				
			case 'radio':
				var rad = $A(this.form[el.name]);
				var ok = false;
				rad.each(function(e,i){
					if(e.checked){
						ok = true;
					}
				});
				if(!ok){
					valid = false;
					//this.setMsg(rad.getLast(), '请选择一项');
					this.setMsg(el,'请选择一项');
				}else{
					valid = true;
					//this.clearMsg(rad.getLast());
				}
				break;
				
		}
		return valid;
	},
	
	setMsg: function(el, msg){
		if(msg == undefined){
			msg = el.getAttribute("msg");
		}
		if(this.options.showErrorsInline){
			tip = $('tip_'+el.name);
			if(tip != undefined){
				tip.addClass(this.options.errorMsgClass).set('text',msg);
			}else{
				if(el.error == undefined){
				    el.error = new Element('span').addClass(this.options.errorMsgClass).set('text',msg).injectAfter(el);
				}else{
					el.error.set('text',msg);
				}
			}
			el.addClass(this.options.errorClass);
		}
	},
	
	clearMsg: function(el){
		el.removeClass(this.options.errorClass);
		tip = $('tip_'+el.name);
		if(tip != undefined){
			tip.removeClass(this.options.errorMsgClass).set('text','');
		}else{
			if(el.error != undefined){
				el.error.destroy();
				el.error = undefined;
			}
		}
	},
	
	isDate : function(str){
		var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/); 
		if(r == null)return false; 
		var d = new Date(r[1], r[3]-1, r[4]); 
		return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]);
	},
	
	isDateTime : function(str){
		var reg = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/; 
		var r = str.match(reg); 
		if(r == null) return false; 
		var d = new Date(r[1], r[3]-1,r[4],r[5],r[6],r[7]); 
		return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]&&d.getHours()==r[5]&&d.getMinutes()==r[6]&&d.getSeconds()==r[7]);
	},
	
	isTime : function(str){
		var a = str.match(/^(\d{1,2})(:)?(\d{1,2})\2(\d{1,2})$/);
		if (a == null) return false;
		if (a[1]>24 || a[3]>60 || a[4]>60) return false
	    return true;
     }

});

Validate.implement(new Options);
Validate.implement(new Events);