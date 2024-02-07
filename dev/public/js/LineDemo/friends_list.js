
var model; // FriendsListクラスのインスタンス

$(() => {
	
	model = new FriendsList();

});

function get_friends_list(){
	model.get_friends_list();
}

