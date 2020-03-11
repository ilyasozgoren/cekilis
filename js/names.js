var imported = false;
var colors = ["#ABC8E4","#628CB6","#003366","#001948", "#000C24"];
function getColor(n) {
	if (n < colors.length) {
		return colors[n];
	} else {
		return colors[colors.length - 1];
	}
}

function importTabSeparated(ts) {
	imported = [];
	var rows = ts.split("\n");
	for (var row of rows) {
		var nameWithPoints = row.split("\t");
		var name = nameWithPoints[0];
		var points = nameWithPoints[1] === undefined ? 1 : parseInt(nameWithPoints[1], 10);
		imported.push({name: name, points: points});
	}
	$('.enter-names').hide(200, function(){
		makeTicketsWithPoints();
	});
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
var key = getParameterByName('key');

if(key){
	var gridIds = ['1','o6d'];
	function getFromGoogle(i){
		$.ajax({ 
			url: 'https://spreadsheets.google.com/feeds/list/'+key+'/'+gridIds[i]+'/public/values?alt=json',
			type: 'get',
			dataType: "jsonp",
			error: function(){
				if(i+1 < gridIds.length)
					getFromGoogle(i+1);
			},
			timeout:5000,
			success: function(list){
				var keys = []; 
				for(var name in list.feed.entry[0])
					if(name.indexOf('gsx$') == 0)
						keys.push(name);

				var firstName = false;
				var lastName = false;
				var fullName = false;
				for(var i=0; i<keys.length; i++){
					if(keys[i].toLowerCase().indexOf('first') > 0){
						firstName = keys[i];
					}
					if(keys[i].toLowerCase().indexOf('last') > 0){
						lastName = keys[i];
					}
					if(keys[i].toLowerCase().indexOf('name') > 0){
						fullName = keys[i];
					}
				}

				var names = list.feed.entry.map(function(entry){
					var result = '';
					if(firstName && lastName) return entry[firstName].$t + ' ' + entry[lastName].$t;
					if(firstName) return entry[firstName].$t;
					if(lastName) return entry[lastName].$t;
					if(fullName) return entry[fullName].$t;
					return false;
				}).filter(function(name){
					return name;
				}).map(function(name){
					return {
						name:name
					}
				});

				if(names.length > 0){
					imported = names;
					$('.enter-names').hide();
					makeTicketsWithPoints();
				}
			}
		});
	}
	getFromGoogle(0);

}