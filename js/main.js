//送出補派彩的要求 就是送出的表單
function run_re_do_lty(){
	var URLs="../server/service/ser_remedy_lottery_all.php";
	$.ajax({
		url: URLs,
		data: $("#re_do_lty").serialize(),
		type:"POST",
		dataType:"text",
		beforeSend:function(){
		},
		complete:function(){
		},
		success: function(msg){
		alert(msg);
		},
		error:function(xhr, ajaxOptions, thrownError){
		alert(xhr.status); 
		alert(thrownError);
		alert("執行失敗"); 
		}
	});
}
//送出補開期數的要求 就是送出的表單
function run_ins_draws(){
	var URLs="../server/service/ser_ins_lack_draws.php";
	$.ajax({
		url: URLs,
		data: $("#ins_draws").serialize(),
		type:"POST",
		dataType:"text",
		beforeSend:function(){
		},
		complete:function(){
		},
		success: function(msg){
		alert(msg);
		},
		error:function(xhr, ajaxOptions, thrownError){
		alert(xhr.status); 
		alert(thrownError);
		alert("執行失敗"); 
		}
	});
}
//送出補做調盤表的要求 就是送出的表單
function run_ins_draws_odds(){
	var URLs="../server/service/ser_ins_lack_draws_odds.php";
	$.ajax({
		url: URLs,
		data: $("#ins_draws_odds").serialize(),
		type:"POST",
		dataType:"text",
		beforeSend:function(){
		},
		complete:function(){
		},
		success: function(msg){
		alert(msg);
		},
		error:function(xhr, ajaxOptions, thrownError){
		alert(xhr.status); 
		alert(thrownError);
		alert("執行失敗"); 
		}
	});
}
//送出讀文字檔 寫資料庫 的要求 就是送出的表單
function run_read_txt_to_db(){
	var URLs="../lib/read_txt_to_db.php";
	$.ajax({
		url: URLs,
		data: $("#read_txt_to_db").serialize(),
		type:"POST",
		dataType:"text",
		beforeSend:function(){
		},
		complete:function(){
		},
		success: function(msg){
		alert(msg);
		},
		error:function(xhr, ajaxOptions, thrownError){
		alert(xhr.status); 
		alert(thrownError);
		alert("執行失敗"); 
		}
	});
}
//日期被選擇時 所做的事情
/*
*送出 遊戲跟日期 
*取得當日已結算的期數 
*塞入 下拉選單
*/
function date_onSelect_result(){
	var URLs="get_draws.php";
	$.ajax({
		url: URLs,
		//*送出 遊戲跟日期 
		data: {
			'g':$('#g').val(),
			'd':$("#date").val()
		},
		type:"POST",
		dataType:"json",
		//*取得當日已結算的期數 
		success: function(draws){
			var game=$('#g').val();
			//*初始化 下拉選單
			$("#result_sn").empty();
			$("#result").empty();
			//*塞入 下拉選單
			for (var i = 0; i < draws.length; i++) {
				$("#result_sn").append('<option value='+draws[i]+'>'+draws[i]+'</option>');
			}
			switch(game){
				case 'klc':
					for (var j = 1; j <= 8; j++) {
						$("#result").append('號碼'+j+':');
						$("#result").append('<input id="'+j+'"type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="'+j+'">');
					}
					break;
				case 'ssc':
					for (var j = 1; j <= 5; j++) {
						$("#result").append('號碼'+j+':');
						$("#result").append('<input id="'+j+'"type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="'+j+'">');
					}
					break;
				case 'pk':
					for (var j = 1; j <= 10; j++) {
						$("#result").append('號碼'+j+':');
						$("#result").append('<input id="'+j+'"type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="'+j+'">');
					}
					break;
				case 'nc':
					for (var j = 1; j <= 8; j++) {
						$("#result").append('號碼'+j+':');
						$("#result").append('<input id="'+j+'"type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="'+j+'">');
					}
					break;
				case 'kb':
					for (var j = 1; j <= 21; j++) {
						$("#result").append('號碼'+j+':');
						$("#result").append('<input id="'+j+'"type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="'+j+'">');
					}
					break;
			}
		}
	});
}
//日期被選擇時 所做的事情
/*
*送出 遊戲跟日期 
*取得當日已結算的期數 
*塞入 下拉選單
*/
function date_onSelect_chg_result(){
	var URLs="get_draws.php";
	$.ajax({
		url: URLs,
		//*送出 遊戲跟日期 
		data: {
			'g':$('#g').val(),
			'd':$("#date").val()
		},
		type:"POST",
		dataType:"json",
		//*取得當日已結算的期數 
		success: function(draws){
			//*初始化 下拉選單
			$("#result_sn").empty();
			//*塞入 下拉選單
			for (var i = 0; i < draws.length; i++) {
				$("#result_sn").append('<option value='+draws[i]+'>'+draws[i]+'</option>');
			}
		}
	});
}
//日期被選擇時 所做的事情
/*
*送出 遊戲跟日期 
*取得當日已結算的期數 
*塞入 下拉選單
*/
function date_re_onSelect_result(){
	var URLs="get_draws.php";
	$.ajax({
		url: URLs,
		//*送出 遊戲跟日期 
		data: {
			'g':$('#re_g').val(),
			'd':$("#re_date").val()
		},
		type:"POST",
		dataType:"json",
		//*取得當日已結算的期數 
		success: function(draws){
			var game=$('#g').val();
			//*初始化 下拉選單
			$("#re_result_sn").empty();
			$("#re_result").empty();
			//*塞入 下拉選單
			
			for (var i = 0; i < draws.length; i++) {
				$("#re_result_sn").append('<option value='+draws[i]+'>'+draws[i]+'</option>');
			}
		}
	});
}
//送出手動開獎的要求 就是送出的表單
function run_ins_result(){
	var URLs="../server/service/mo.ins_award.php";
	var result_obj=$("#result input");
	var objlength=result_obj.length;
	var resary=new Object();
	for(i=0;i<objlength;i++){
		resary[result_obj[i].id]=$('#'+result_obj[i].id).val();
	}
	resjson=JSON.stringify(resary);
	$.ajax({
		url: URLs,
		data: {
			'game':$('#g').val(),
			'rpt_date':$("#date").val(),
			'draws_num':$("#result_sn").val(),
			'result':resjson
		},
		type:"POST",
		dataType:"text",
		beforeSend:function(){
		},
		complete:function(){
		},
		success: function(msg){
		alert(msg);
		},
		error:function(xhr, ajaxOptions, thrownError){
		alert(xhr.status); 
		alert(thrownError);
		}
	});
}
//送出切換站台 修正結果
function run_chg_result(){
	var URLs="../server/service/ser_chg_result.php";
	$.ajax({
		url: URLs,
		data: {
			'game':$('#g').val(),
			'rpt_date':$("#date").val(),
			'site':$("#site").val(),
			'draws_num':$("#result_sn").val(),
		},
		type:"POST",
		dataType:"text",
		beforeSend:function(){
		},
		complete:function(){
		},
		success: function(msg){
		alert(msg);
		},
		error:function(xhr, ajaxOptions, thrownError){
		alert(xhr.status); 
		alert(thrownError);
		}
	});
}
//刪除key錯的結果期數
function run_re_ins_result(){
	var URLs="api.php?c=del_result";
	$.ajax({
		url: URLs,
		data: {
			 'date':$('#re_date').val()
			,'game':$('#re_g').val()
			,'draws':$('#re_result_sn').val()
		},
		type:"POST",
		dataType:"text",
		success: function(data){
			//console.log(data);
			alert(data);
		}
	});	
}
//送出寫文字檔 的要求 就是送出的表單
function run_write_result_txt(){
	var URLs="../lib/write_result_txt.php";
	$.ajax({
		url: URLs,
		data: $("#write_result_txt").serialize(),
		type:"POST",
		dataType:"text",
		beforeSend:function(){
		},
		complete:function(){
		},
		success: function(msg){
		alert(msg);
		},
		error:function(xhr, ajaxOptions, thrownError){
		alert(xhr.status); 
		alert(thrownError);
		alert("執行失敗"); 
		}
	});
}