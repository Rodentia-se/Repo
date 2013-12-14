// Original Script Posted by Dylan Verheul http://www.dyve.net/jquery/?autocomplete
// Modified by Ritesh Agrawal 
// 11/16/2006
// Modification: Added a new option to specify inputSeparator.
// It checks the input field for the separator and breaks input value into different tokens and provides option
// for the last token

$.autocomplete = function(input, options) {
	// Create a link to self
	var me = this;

	// Create jQuery object for input element
	var $input = $(input).attr("autocomplete", "off");;

	// Apply inputClass if necessary
	if (options.inputClass) $input.addClass(options.inputClass);

	// Create results
	var results = document.createElement("div");
	// Create jQuery object for results
	var $results = $(results);
	// Set default values for results
	var pos = findPos(input);
	$results.hide().addClass(options.resultsClass).css({
		position: "absolute",
		top: (pos.y + input.offsetHeight) + "px",
		left: pos.x + "px"
	});
	// Add to body element
	$("body").append(results);

	input.autocompleter = me;
	input.lastSelected = $input.val();

	var timeout = null;
	var prev = "";
	var active = -1;
	var cache = {};
	var keyb = false;

	$input
	.keydown(function(e) {
		switch(e.keyCode) {
			case 38: // up
				e.preventDefault();
				moveSelect(-1);
				break;
			case 40: // down
				e.preventDefault();
				moveSelect(1);
				break;
			case 9:  // tab
			case 13: // return
				if (selectCurrent()) {
					e.preventDefault();
				}
				break;
			default:
				active = -1;
				if (timeout) clearTimeout(timeout);
				timeout = setTimeout(onChange, options.delay);
				break;
		}
	})
	.blur(function() {
		hideResults();
	});

	hideResultsNow();

	//remove starting whitespaces
	function LTrim( value ) {
		
		var re = /\s*((\S+\s*)*)/;
		return value.replace(re, "$1");
		
	}
	
	// Removes ending whitespaces
	function RTrim( value ) {
		
		var re = /((\s*\S+)*)\s*/;
		return value.replace(re, "$1");
		
	}
	
	// Removes leading and ending whitespaces
	function trim( value ) {
		
		return LTrim(RTrim(value));
		
	}

	function onChange() {
		var v = trim($input.val());
		if(options.inputSeparator.length != 0){
			var broken = v.split(';');
			v = trim(broken[broken.length-1]);
		}
		if (v == prev) return;
		prev = v;
		if (v.length >= options.minChars) {
			$input.addClass(options.loadingClass);
			requestData(v);
		} else {
			$input.removeClass(options.loadingClass);
			$results.hide();
		}
	};

 	function moveSelect(step) {

		var lis = $("li", results);
		if (!lis) return;

		active += step;

		if (active < 0) {
			active = 0;
		} else if (active >= lis.size()) {
			active = lis.size() - 1;
		}

		lis.removeClass("over");

		$(lis[active]).addClass("over");

		// Weird behaviour in IE
		// if (lis[active] && lis[active].scrollIntoView) {
		// 	lis[active].scrollIntoView(false);
		// }

	};

	function selectCurrent() {
		var li = $("li.over", results)[0];
		if (!li) {
			var $li = $("li", results);
			if (options.selectOnly) {
				if ($li.length == 1) li = $li[0];
			} else if (options.selectFirst) {
				li = $li[0];
			}
		}
		if (li) {
			selectItem(li);
			return true;
		} else {
			return false;
		}
	};

	function selectItem(li) {
		if (!li) {
			li = document.createElement("li");
			li.extra = [];
			li.selectValue = "";
		}
		var v = $.trim(li.selectValue ? li.selectValue : li.innerHTML);
		input.lastSelected = v;
		prev = v;
		$results.html("");
		if(options.inputSeparator.length != 0){
			var broken = $input.val().split(';');
			broken.splice(broken.length-1, 1, v);
			$input.val(broken.join(options.inputSeparator + " "));
		}else{
			$input.val(v);
		}
		hideResultsNow();
		if (options.onItemSelect) setTimeout(function() { options.onItemSelect(li) }, 1);
	};

	function hideResults() {
		if (timeout) clearTimeout(timeout);
		timeout = setTimeout(hideResultsNow, 200);
	};

	function hideResultsNow() {
		if (timeout) clearTimeout(timeout);
		$input.removeClass(options.loadingClass);
		if ($results.is(":visible")) {
			$results.hide();
		}
		if (options.mustMatch) {
			var v = $input.val();
			if (v != input.lastSelected) {
				selectItem(null);
			}
		}
	};

	function receiveData(q, data) {
		if (data) {
			$input.removeClass(options.loadingClass);
			results.innerHTML = "";
			if ($.browser.msie) {
				// we put a styled iframe behind the calendar so HTML SELECT elements don't show through
				$results.append(document.createElement('iframe'));
			}
			results.appendChild(dataToDom(data));
			$results.show();
		} else {
			hideResultsNow();
		}
	};

	function parseData(data) {
		if (!data) return null;
		var parsed = [];
		var rows = data.split(options.lineSeparator);
		for (var i=0; i < rows.length; i++) {
			var row = $.trim(rows[i]);
			if (row) {
				parsed[parsed.length] = row.split(options.cellSeparator);
			}
		}
		return parsed;
	};

	function dataToDom(data) {
		var ul = document.createElement("ul");
		var num = data.length;
		for (var i=0; i < num; i++) {
			var row = data[i];
			if (!row) continue;
			var li = document.createElement("li");
			if (options.formatItem) {
				li.innerHTML = options.formatItem(row, i, num);
				li.selectValue = row[0];
			} else {
				li.innerHTML = row[0];
			}
			var extra = null;
			if (row.length > 1) {
				extra = [];
				for (var j=1; j < row.length; j++) {
					extra[extra.length] = row[j];
				}
			}
			li.extra = extra;
			ul.appendChild(li);
			$(li).hover(
				function() { $("li", ul).removeClass("over"); $(this).addClass("over"); },
				function() { $(this).removeClass("over"); }
			).click(function(e) { e.preventDefault(); e.stopPropagation(); selectItem(this) });
		}
		return ul;
	};

	function requestData(q) {
		if (!options.matchCase) q = q.toLowerCase();
		var data = options.cacheLength ? loadFromCache(q) : null;
		if (data) {
			receiveData(q, data);
		} else {
			$.get(makeUrl(q), function(data) {
				data = parseData(data)
				addToCache(q, data);
				receiveData(q, data);
			});
		}
	};

	function makeUrl(q) {
		var url = options.url + "?q=" + q;
		for (var i in options.extraParams) {
			url += "&" + i + "=" + options.extraParams[i];
		}
		return url;
	};

	function loadFromCache(q) {
		if (!q) return null;
		if (cache[q]) return cache[q];
		if (options.matchSubset) {
			for (var i = q.length - 1; i >= options.minChars; i--) {
				var qs = q.substr(0, i);
				var c = cache[qs];
				if (c) {
					var csub = [];
					for (var j = 0; j < c.length; j++) {
						var x = c[j];
						var x0 = x[0];
						if (matchSubset(x0, q)) {
							csub[csub.length] = x;
						}
					}
					return csub;
				}
			}
		}
		return null;
	};

	function matchSubset(s, sub) {
		if (!options.matchCase) s = s.toLowerCase();
		var i = s.indexOf(sub);
		if (i == -1) return false;
		return i == 0 || options.matchContains;
	};

	this.flushCache = function() {
		cache = {};
	};

	this.setExtraParams = function(p) {
		options.extraParams = p;
	};

	function addToCache(q, data) {
		if (!data || !q || !options.cacheLength) return;
		if (!cache.length || cache.length > options.cacheLength) {
			cache = {};
			cache.length = 1; // we know we're adding something
		} else if (!cache[q]) {
			cache.length++;
		}
		cache[q] = data;
	};

	function findPos(obj) {
		var curleft = obj.offsetLeft || 0;
		var curtop = obj.offsetTop || 0;
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
		return {x:curleft,y:curtop};
	}
}

$.fn.autocomplete = function(url, options) {
	// Make sure options exists
	options = options || {};
	// Set url as option
	options.url = url;
	
	// Set default values for required options
	options.inputClass = options.inputClass || "ac_input";
	options.resultsClass = options.resultsClass || "ac_results";
	options.lineSeparator = options.lineSeparator || "\n";
	options.cellSeparator = options.cellSeparator || "|";
	options.minChars = options.minChars || 1;
	options.delay = options.delay || 400;
	options.matchCase = options.matchCase || 0;
	options.matchSubset = options.matchSubset || 1;
	options.matchContains = options.matchContains || 0;
	options.cacheLength = options.cacheLength || 1;
	options.mustMatch = options.mustMatch || 0;
	options.extraParams = options.extraParams || {};
	options.loadingClass = options.loadingClass || "ac_loading";
	options.selectFirst = options.selectFirst || false;
	options.selectOnly = options.selectOnly || false;
	options.inputSeparator = options.inputSeparator || '';

	this.each(function() {
		var input = this;
		new $.autocomplete(input, options);
	});

	// Don't break the chain
	return this;
}
