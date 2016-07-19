$=jQuery;
$(function(){
	_.registerLocale('ru', {
		' on ': ' на ',
		' credit': ' кредит',
		'Play it': 'Кинуть ЛОХа',
		'Result: ': 'В итоге: ',
		'Find Opponent': 'Подбираем ЛОХа',
		'You has a lot of credits. Enough!': 'У тебя и так много, хватит!',
		'You took draw!': 'Ха-ха, ничья! Может в следующий раз повезёт =).',
		'All bets are off': 'Ставка сделана. Ставок больше нет.',
		'Your drop:': 'Фортуна подарила тебе:',
		'You got 100 creadits if register': 'Буду давать по 100 кредитов если зарегистрируешься!',
		'Opponent drop:': 'Твоему оппоненту выпало:',
		'Please register to continue': 'Зарегистрироваться и играть дальше',
		'We need more Gold! (C)': 'Нужно больше золота! (С)',
		'Wait for opponent bet': 'Ждем ставки от ЛОХа',
		'You cheated ': 'Без ЛОХа и жизнь плоха, вы кинули ',
		'Opponent bet is less, so play his bet': 'Ставка ЛОХа оказалась меньше твоей, играем по ней',
		'Your bet was already finished. Try more': 'Видимо что-то случилось, давай сыграем ещё!',
		'You was cheated by ': 'Ха-ха, ЛОХ! Не твой день сегодня, тебя кинул ',
	});

	if ($('#play').data('state') == 'waitOpponent') {
		var timerId = setTimeout(checkOpponent, 5*100);
		msg = _('Wait for opponent bet');
		sendMsg(msg, 'info')
	}

	$('#fixedbet').click(function(){
		var bet = $('#bet').val();
		if (!$(this).prop('checked'))
			bet = 0;
		$.get('/game/fixbet?state=' + bet);
	});

	$('#more').click(function(){
		var bet = $('#bet').val();
		var credits = parseInt($('#credits').html());

		if ($('#fixedbet').prop('checked'))
			$('#fixedbet').click();

		if (bet < 10 && bet <= credits) {
			$('#bet').val(parseInt(bet)+1);
		};
		if ($('#bet').val() > credits) {
			$('#bet').val(credits);
		};
	});

	$('#less').click(function(){
		var bet = $('#bet').val();
		var credits = parseInt($('#credits').html());

		if ($('#fixedbet').prop('checked'))
			$('#fixedbet').click();

		if (bet > 1) {
			$('#bet').val(bet-1);
		};
		if (bet > credits) {
			$('#bet').val(credits);
			return;
		};
	})

	$('#play').click(function(){
		if ($('#play').data('state') == 'needRegister')
		{
			window.location = '/user/register';
		}
		var bet = $('#bet').val();
		var credits = parseInt($('#credits').html());
		if (bet > credits) {
			$('#bet').val(credits);
			if ($('#fixedbet').prop('checked'))
				$('#fixedbet').click();
			return false;
		};
		if ($('#bet').val() < 1) {
			if (!isGuest) {
				$('#play').html(_('We need more Gold! (C)')).prop('disabled', true);
			}
			else {
				$('#play').html(_('Please register to continue')).data('state', 'needRegister').prop('disabled', false);
			}

			if ($('#fixedbet').prop('checked'))
				$('#fixedbet').click();
			return false;
		};

		$('#play').html(_('Find Opponent')).prop('disabled', true);

		$.get('/game/play?bet='+bet, function(response){
			if (response.hasOpponent == true) {
				setPlayedGame(response);
			}
			else {
				$('#play').html(_('Wait for opponent bet')).data('state', 'waitOpponent').prop('disabled', true);
				msg = _('Wait for opponent bet');

				timerId = setTimeout(checkOpponent, 5*100);

				sendMsg(msg, 'info');
			}
			return false;
		});
	});

	function checkOpponent()
	{
		$.get('/game/check', function(response){
			//response = JSON.parse(response);

			if (response.gameNotExist) {
				$('#play').html(_('Play it')).data('state', 'ready').prop('disabled', false);
				var msg = _('Your bet was already finished. Try more');
				sendMsg(msg, 'info');
				return false;
			}

			if (response.hasOpponent) {
				setPlayedGame(response);
				clearTimeout(timerId);
				return false;
			}

			if ($('#play').data('state') == 'waitOpponent')
				timerId = setTimeout(checkOpponent, 5*1000);
		});
	}

	var myCounter = null;
	var config = {
		digitsNumber : 5,
		direction : Counter.ScrollDirection.Upwards,
		characterSet : Counter.DefaultCharacterSets.numericUp,
		charsImageUrl : "/images/numeric_up_whitebg5.png",
		markerImageUrl : "/images/marker.png",
		scrollAnimation : Counter.ScrollAnimation.FixedSpeed,
		value: 0
	};
	var yourCounter = new Counter("yourDrop", config);
	var oppCounter = new Counter("oppDrop", config);
	yourCounter.setValue(0, 1000);
	oppCounter.setValue(0, 1000);

	function startDigitsShow(response)
	{
		var msg = $('#messages .alert').html();
		/* slotMachine
		yourTimerId = window.setTimeout(function(){
			yourCounter.setValue(response.yourDrop, 1500, function(){
				clearTimeout(yourTimerId);
			});
		}, 500);*/
		response.yourDrop = '' + response.yourDrop;
		yourCounter.setValue(0, 100);
		yourTimerId = window.setTimeout(function(){
			yourCounter.add(response.yourDrop.substring(4),1000);
			yourTimerId = window.setTimeout(function(){
				yourCounter.add(parseInt(response.yourDrop.substring(3,4) + '0'), 1000);
				yourTimerId = window.setTimeout(function(){
					yourCounter.add(parseInt(response.yourDrop.substring(2,3) + '00'), 1000);
					yourTimerId = window.setTimeout(function(){
						yourCounter.add(parseInt(response.yourDrop.substring(1,2) + '000'), 1000);
						yourTimerId = window.setTimeout(function(){
							yourCounter.add(parseInt(response.yourDrop.substring(0,1) + '0000'), 1000);
							clearTimeout(yourTimerId);
						}, 1000);
					}, 1000);
				}, 1000);
			}, 1000);
		},1000);

		/* opp slotMachine
		oppTimerId = window.setTimeout(function(){
			oppCounter.setValue(response.oppDrop, 1800, function(){
				clearTimeout(oppTimerId);
				msg += _('Your drop:') + response.yourDrop + '<br>';
				msg += _('Opponent drop:') + response.oppDrop + '<br>';

				if (response.win == 'win') {
					msg += _('Result: ') + '<span class="green">' + _('You cheated ') + response.opponent + _(' on ') + response.bet + _(' credit') + '.</span>';
				}
				else {
					msg += _('Result: ') + '<span class="red">' + _('You was cheated by ') + response.opponent + _(' on ') + response.bet + _(' credit') + '.</span>';
				}

				$('#credits').html(response.credits);

				if (response.canNotPlay) {
					$('#play').html(_('We need more Gold! (C)')).prop('disabled', true);
				}
				else {
					$('#play').html(_('Play it')).data('state', 'ready').prop('disabled', false);
				}

				sendMsg(msg, 'success');
			});
		}, 600);*/

		response.oppDrop = '' + response.oppDrop;
		oppCounter.setValue(0, 100);
		oppTimerId = window.setTimeout(function(){
			oppCounter.add(response.oppDrop.substring(4),1000);
			oppTimerId = window.setTimeout(function(){
				oppCounter.add(parseInt(response.oppDrop.substring(3,4) + '0'), 1000);
				oppTimerId = window.setTimeout(function(){
					oppCounter.add(parseInt(response.oppDrop.substring(2,3) + '00'), 1000);
					oppTimerId = window.setTimeout(function(){
						oppCounter.add(parseInt(response.oppDrop.substring(1,2) + '000'), 1000);
						oppTimerId = window.setTimeout(function(){
							oppCounter.add(parseInt(response.oppDrop.substring(0,1) + '0000'), 1000, function(){
								clearTimeout(oppTimerId);
								msg += _('Your drop:') + response.yourDrop + '<br>';
								msg += _('Opponent drop:') + response.oppDrop + '<br>';

								if (response.win == 'win') {
									msg += _('Result: ') + '<span class="green">' + _('You cheated ') + response.opponent + _(' on ') + response.bet + _(' credit') + '.</span>';
								}
								else {
									msg += _('Result: ') + '<span class="red">' + _('You was cheated by ') + response.opponent + _(' on ') + response.bet + _(' credit') + '.</span>';
								}

								$('#credits').html(response.credits);

								if (response.canNotPlay) {
									if (!isGuest) {
										$('#play').html(_('We need more Gold! (C)')).prop('disabled', true);
									}
									else {
										$('#play').html(_('Please register to continue')).data('state', 'needRegister').prop('disabled', false);
									}
								}
								else {
									$('#play').html(_('Play it')).data('state', 'ready').prop('disabled', false);
								}

								sendMsg(msg, 'success');
							});
							clearTimeout(oppTimerId);
						}, 1000);
					}, 1000);
				}, 1000);
			}, 1000);
		},1000);

	}

	function setPlayedGame(response)
	{
		var msg = '';

		if (response.partBet) {
			if (!$('#fixedbet').prop('checked'))
				$('#bet').val(response.leftBet);
			msg += _('Opponent bet is less, so play his bet') + '<br>';
		}

		msg += _('All bets are off') + '<br>';

		sendMsg(msg, 'success');

		startDigitsShow(response);
		return false;
	}

});

function creditRequest()
{
	var credits = parseInt($('#credits').html());
	var msg = '';
	if (isGuest) {
		alert(_('You got 100 creadits if register'));
	}
	$('#creditRequest').toggleClass('active');
	$.get('/game/creditrequest', function(response) {
		if (response.enough) {
			$('#creditRequest').toggleClass('active');
			msg += _('You has a lot of credits. Enough!') + '<br>';
			sendMsg(msg, 'info');
			return false;
		}

		$('#creditRequest').toggleClass('active');
		$('#credits').html(response.credits);
		if ($('#play').data('state') == 'needRegister')
		{
			$('#play').html(_('Play it')).data('state', 'ready').prop('disabled', false);
		}

		return false;
	});
}


function sendMsg(msg, type)
{
	msg = '<div class="alert alert-'+type+'">' + msg + '</div>';
	$('#messages').html(msg);
}

function _(str, locale)
{
	locale = locale || _.defaultLocale;
	if (_.data.hasOwnProperty(locale) && typeof _.data[locale] == 'object') {
		if (_.data[locale].hasOwnProperty(str)) {
			return _.data[locale][str];
		}
	}
	return str;
}

_.defaultLocale = 'ru';
_.data = {
	ru: {}
};
_.registerLocale = function registerLocale(locale, data) {
	if (!_.data.hasOwnProperty(locale)) {
		_.data[locale] = {};
	}
	for (var str in data) {
		if (data.hasOwnProperty(str)) {
			_.data[locale][str] = data[str];
		}
	}
}