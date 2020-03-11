var inProgress = false;
var size = 60;
function map(a, f){
	for(var i=0; i<a.length; i++){
		f(a[i], i);
	}
}
function shuffle(array) {
   var m = array.length, t, i;
  // // While there remain elements to shuffle…
   while (m) {
  //   // Pick a remaining element…
     i = Math.floor(Math.random() * m--);
  //   // And swap it with the current element.
     t = array[m];
     array[m] = array[i];
     array[i] = t;
   }
  return array;
}

function getNames() {
	return $('.name-text-field').val().split('\n').filter(function(name) {
		return name.trim();
	});
}

function process(){
	var names = $('.name-text-field').val().split('\n');
	imported = [];
	map(getNames(), function(name){
		imported.push({'name':name});
	});
	$('.enter-names').hide(200, function(){
		//$('.navbar').hide();
		makeTicketsWithPoints();
	});
}

$(document).ready(function(){
	if(imported && imported.length > 0) {
		$('.enter-names').hide();

		makeTicketsWithPoints();
	}

	$('.name-text-field').on('input', function() {
		$('#participant-number').text(getNames().length || '');
	});
});

function elementInViewport(el) {
  var top = el.offsetTop;
  var left = el.offsetLeft;
  var width = el.offsetWidth;
  var height = el.offsetHeight;

  while(el.offsetParent) {
    el = el.offsetParent;
    top += el.offsetTop;
    left += el.offsetLeft;
  }

  return (
    top >= window.pageYOffset &&
    left >= window.pageXOffset &&
    (top + height) <= (window.pageYOffset + window.innerHeight) &&
    (left + width) <= (window.pageXOffset + window.innerWidth)
  );
}

function remainingParticipants() {
	var participants = 0;
	for (var ticket of tickets) {
		if (ticket.points > 0) {
			participants++;
		}
	}
	return participants;
}

function Ticket(name, points){
	this.name = name;
	if(typeof(points) == "number")
		this.points = points;
	else
		this.points = 1;

	this.dom = $("<div class='ticket'>").text(name);
	this.fixPosition = function(){
		var me = this;
		this.dom.css({
			'position':'absolute',
			'top': me.dom.offset().top,
			'left':me.dom.offset().left,
			'background': getColor(me.points),
		});
	};
	this.decrement = function(length, callback){
		var me = this;
		this.points--;
		if(this.points == 0){
			var directions = ['up', 'down', 'left', 'right'];
			this.dom.css({'background-color':colors[0]}).hide('drop', {direction:directions[length%directions.length]}, length <= 3 ? 750 : 3000/length, function(){
				callback();
			});
			$('#participant-number').text(remainingParticipants() + '/' + tickets.length);
		}
		else{
			this.dom.css({
				'background-color': getColor(me.points),
			})
			setTimeout(function() {
				callback();
			}, length == 2 ? 800 : 1);
		}
	}
}

var tickets = [];
var removeWinners = true;

function toggleRemoveWinners() {
	removeWinners = !removeWinners;
}

var removeDuplicateNames = function(data){
	var seen = {};
	return data.filter(function(d){
		if(seen[d.name.toLowerCase()]){
			return false;
		}
		seen[d.name.toLowerCase()] = true;
		return true;
	});
}

function standardizedImported() {
	var namePoints = {};
	for (var entry of imported) {
		var points = (entry.points === undefined ? 1 : entry.points);
		if (entry.name in namePoints) {
			namePoints[entry.name] += points;
		} else {
			namePoints[entry.name] = points;
		}
	}
	imported = [];
	for (var name in namePoints) {
		imported.push({name: name, points: namePoints[name]});
	}
	return imported;
}

var makeTicketsWithPoints = function(){
	standardizedImported();

	tickets = [];
	$('.ticket').remove();

	map(removeDuplicateNames(imported), function(tdata){
		if (tdata.points === undefined) {
			tdata.points = 1;
		}
		if (tdata.points > 0) {
			var t = new Ticket(tdata.name, tdata.points);
			t.dom.appendTo($('#secim'));
			tickets.push(t);
		}
	});
	tickets.reverse();
	size = 30;
	$('.ticket').css('font-size', size + 'px');
	//alert("dddd");
	while(!elementInViewport(tickets[0].dom.get(0)) && size > 10){
		size--;
		$('.ticket').css('font-size', size + 'px');
	}
	//alert(size);

	$('#participant-number').css('width', '').text(tickets.length);
	setTimeout(function() {
		map(tickets, function(ticket){
			ticket.fixPosition();
		});
		//$('body').unbind('click').click(function(){
			//alert("sdsd")
			var width = $('#participant-number').text(tickets.length + '/' + tickets.length).width();
			$('#participant-number').css('width', width + 'px'); 
			pickName();
		//});
	}, 500);
}

var getChoices = function(){
	//alert(tickets.points)
	var result = [];
	map(tickets, function(ticket){
		for (var i = 0; i < ticket.points; i++)
			result.push(ticket)
	});
	return result;
}

$(window).resize(function(){
	if(!inProgress)
		makeTicketsWithPoints(tickets);
});

function randomInt(max) {
	return Math.floor(Math.random() * max);
}

var pickName = function(){
	inProgress = true;
	$('.ticket').unbind('click');
	$('body').unbind('click');
	
	var choices = getChoices();
	//console.log(getChoices())
	//alert("sfds")
	if(remainingParticipants() > 1){
		var remove = randomInt(choices.length);
		choices[remove].decrement(choices.length, function(){
			pickName();
		});
	}else{
		if (removeWinners) {
			var winner = choices[0].name;
			for (var entry of imported) {
				if (entry.name == winner) {
					entry.points--;
				}
			}
		}
		choices = $(choices[0].dom);
		var top = choices.css('top');
		var left = choices.css('left');
		var fontsize = choices.css('font-size');
		var width = choices.width();
		choices.click(function(){
			inProgress = false;
			choices.animate({'font-size':fontsize,'top':top,'left':left},'slow');
			$('.ticket').show(500).unbind('click');
			setTimeout(function(){
				makeTicketsWithPoints();
			}, 500);
		});
		//console.log(choices[0].textContent)
		var wnr = choices[0].textContent;
		var winner = wnr.split(" ");
		choices.animate({'font-size':3*size +'px','top':(window.innerHeight/5) + 'px','left':(window.innerWidth/2 - width) + 'px'},1000, function(){
			choices.animate({'left':(window.innerWidth/2 - choices.width()/2) + 'px'}, 'slow');
			choices[0].innerHTML += " <br> <p style='text-align:center'> TEBRİKLER.</p>";
		});
		var rid = getParameterByName('cid');
		$.ajax({  
			type:"POST",  
			url:"api/insertWinner.php",  
			data:{winid:winner[0],rid:rid},  
			success:function(data){ 
			  //console.log(data); 
			   //$('#employee_table').html(data);
			   setTimeout(function(){
					window.location.href = "index.php";
				}, 3000);  
			}  
	 	}); 
	}
}