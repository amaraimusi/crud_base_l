/**
 * 友だちユーザーリスト
 * @since 2024-2-1
 * @version 1.0.0
*/
class FriendsList{
	
	/**
	* コンストラクタ
	* @param {}
	*  - ls_key ローカルストレージキー(省略可） 当クラスを一つの画面で複数生成する場合は必ずセットすること。
	*/
	constructor(param){
		
		this.jqFormTbl = $('#form_tbl');
		if(!this.jqFormTbl[0]) new Error('システムエラー:jqFormTblが空です');
		
		param = this._setParam(param);
		
		this._setEntityToFormTbl(param.ent);
		
		this.param = param;
		
	}
	

	get_friends_list(){
		
		let ent = this._getEntityFromFormTbl();
		this.param.ent = ent;
		this._saveLs();
		
		let fd = new FormData(); // 送信フォームデータ
		let json = JSON.stringify(ent);
		fd.append( "key1", json );
		
		// CSRFトークンを送信フォームデータにセットする。
		let token = jQuery('#csrf_token').val();
		fd.append( "_token", token );
		
		jQuery.ajax({
			type: "post",
			url: '../line_demo/get_friends_list',
			data: fd,
			cache: false,
			dataType: "text",
			processData: false,
			contentType: false,

		}).done((str_json, status, xhr) => {
		
			// 419エラーならトークンの期限切れの可能性のためリロードする（トークンの期限は2時間）
			if(xhr.status == 419)  location.reload(true);

			let res = null;
			try{
				res =jQuery.parseJSON(str_json); //パース
			}catch(e){
				alert('バックエンド側のエラー');
				console.log(str_json);
				$('#err').html(str_json);
				return;
			}
			
			if(res.err_msg == 'logout') location.reload(true); // すでにログアウトになっているならブラウザをリロードする。
			if(res.err_msg) {
				console.log(res.err_msg);
				return;
			}
				
			let data = res.data;
			console.log(data);
			
			this._showAudienceList(data);

		}).fail((xhr, status, errorThrown) => {
		
			// 419エラーならトークンの期限切れの可能性のためリロードする（トークンの期限は2時間）
			if(xhr.status == 419)  location.reload(true);
			alert('通信エラー');
			console.log(status);
			console.log(xhr.responseText);
			$('#err').html(xhr.responseText);
			
		});
		
	}
	
	
	_showAudienceList(data){
		if(!data) return;
		
		let html = this._createHtmlTable(data);
		$('#friends_list').html(html);
		
	}
	
	_createHtmlTable(data){
		
		if(data.length==0){
			return "";
		}
		
		var html = "<table class='table'>";
		
		// 0件目のエンティティからtheadを作成
		html += "<thead><tr>";
	
		var ent0 = data[0];
		for(var field in ent0){
			html += "<th>" + field + "</th>";
		}
		html += "</tr></thead>";
		
		// tbodyの部分を作成
		for(var i in data){
			var ent = data[i];
			html += "<tr>";
			for(var f in ent){
				html += "<td>" + ent[f] + "</td>"
			}
			html += "</tr>";
			
		}
		
		html+= "</table>";
	
		return html;
		
	}
	
	
	_setEntityToFormTbl(ent){
		this.jqFormTbl.find("[name='access_token']").val(ent.access_token);

	}

	_getEntityFromFormTbl(){
		let jqFormTbl = $('#form_tbl');
		
		let access_token = jqFormTbl.find("[name='access_token']").val();

		return {
			access_token:access_token,

		};
		
	}


	/**
	 * デフォルトパラメータを取得する
	 */
	_getDefParam(){
		
		let ent = [
			'access_token', '', 
		];
	
		let defParam = {
			'ent':ent, 
		};
		return defParam;
	}
	
	_setParam(pParam){
		
		// ローカルストレージキーの取得。
		this.ls_key = null;
		if(pParam){
			if(pParam.ls_key){
				this.ls_key = pParam.ls_key;
			}else{
				this._createLsKey();
			}
		}
		
		let lsParam = this._getLsParam(); // ローカルストレージ由来パラメータ
		let defParam = this._getDefParam(); // デフォルトパラメータ
		
		// クローンを作成してメンバにセット（パラメータの値がobject型である場合、参照にあるため干渉が起きてしまうのを避ける）
		this.lsParam = $.extend(true, {}, lsParam);
		this.pParam = $.extend(true, {}, pParam);
		this.defParam = $.extend(true, {}, defParam);
		
		let param = {};
		if(!this._empty(lsParam)){
			param = this._merge(param, lsParam);
		}

		if(!this._empty(pParam)){
			param = this._merge(param, pParam);
		}
		
		param = this._merge(param, defParam);
		this.param = param;
		
		return param;

	}
	
	/**
	 * 引数1のパラメータに引数2のパラメータをマージする。
	 * マージルール→未セット(undefined)ならセットする。
	 */
	_merge(param, param2){
		for(let key in param2){
			if(param[key] === undefined){
				param[key] = param2[key];
			}
		}
		return param;
	}
	

	
	/**
	 * ローカルストレージパラメータを取得する
	 */
	_getLsParam(){
		
		let ls_key = this._getLsKey(); // ローカルストレージキーを取得する
		let param_json = localStorage.getItem(ls_key);
		let lsParam = JSON.parse(param_json);
		if(lsParam == null) lsParam = {};
		return lsParam;
		
	}
	
	/**
	 * ローカルストレージで保存しているパラメータをクリアする
	 */
	clearlocalStorage(){
		let ls_key = this._getLsKey(); // ローカルストレージキーを取得する
		localStorage.removeItem(ls_key);
	}
	
	
	/**
	 * ローカルストレージにパラメータを保存
	 */
	_saveLs(){
		let ls_key = this._getLsKey(); // ローカルストレージキーを取得する
		let param_json = JSON.stringify(this.param);
		localStorage.setItem(ls_key, param_json);
	}
	
	
	/**
	 * ローカルストレージキーを取得する
	 */
	_getLsKey(){
		if(this.ls_key == null){
			this.ls_key = this._createLsKey();
		}
		
		return this.ls_key;
		
	}
	
	/**
	 * ローカルストレージキーを自動生成する。
	 */
	_createLsKey(){
		// ローカルストレージキーを取得する
		let ls_key = location.href; // 現在ページのURLを取得
		ls_key = ls_key.split(/[?#]/)[0]; // クエリ部分を除去
		ls_key += this.constructor.name; // 自分自身のクラス名を付け足す
		return ls_key;
	}
	
	
		// Check empty.
	_empty(v){
		if(v == null || v == '' || v=='0'){
			return true;
		}else{
			if(typeof v == 'object'){
				if(Object.keys(v).length == 0){
					return true;
				}
			}
			return false;
		}
	}
	
	/*　パラメータをデフォルトに戻す。
	*/
	_resetParam(){
		
		for(let key in this.defParam){
			this.param[key] = this.defParam[key];
		}
		
		this._saveLs();
		
		return this.param;
	}
	
	
}