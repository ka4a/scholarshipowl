
/*
 * Slightly Modified Resig's Class
 * By Marko Prelic
 */

/*
 * Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 */
(function(){
	var OBJECT_LOADING = false;
	var OBJECT_TEST_FUNCTION = /xyz/.test(function(){xyz;}) ? /\b_super\b/ : /.*/;

	this.Class = function() {};
	Class.extend = function(properties) {
		var _super = this.prototype;

		OBJECT_LOADING = true;
		var prototype = new this();
		OBJECT_LOADING = false;

		for(var name in properties) {
			prototype[name] =
				typeof properties[name] == "function" &&
				typeof _super[name] == "function" &&
				OBJECT_TEST_FUNCTION.test(properties[name])
			?
				(function(name, fn) {
					return function() {
						var temp = this._super;
						this._super = _super[name];

						var result = fn.apply(this, arguments);
						this._super = temp;
						return result;
					};
			})
				(name, properties[name])
			:
				properties[name];
		}

		function Class() {
			if(!OBJECT_LOADING && this._init ) {
				this._init.apply(this, arguments);

				if(arguments.length == 1 && typeof arguments[0] === "object") {
					var object = arguments[0];

					for(var property in object) {
						this[property] = object[property];
					}
				}
			}
		}

		Class.prototype = prototype;
		Class.prototype.constructor = Class;
		Class.extend = arguments.callee;

		return Class;
	};
})();


/*
 * Element Class
 * By Marko Prelic
 */
var Element = Class.extend({
	_selector: null,

	_init: function(selector) {
		if(typeof selector === "undefined") {
			throw "Undefined Selector";
		}

		this._selector = selector;

        this.$this = $(selector);
	},

	bind: function(event, callback) {
		$(this._selector).bind(event, callback);
	},

	getSelector: function() {
		return this._selector;
	},
	setSelector: function(selector) {
		this._selector = selector;
	},

	getHtml: function() {
		return $(this._selector).html();
	},
	setHtml: function(html) {
		$(this._selector).html(html);
	},
	appendHtml: function(html) {
		$(this._selector).append(html);
	},

	getAttribute: function(name) {
		return $(this._selector).attr(name);
	},
	setAttribute: function(name, value) {
		$(this._selector).attr(name, value);
	},

	getCss: function(name) {
		return $(this._selector).css(name);
	},
	setCss: function(name, value) {
		$(this._selector).css(name, value);
	}
});



/*
 * FormElement Class
 * By Marko Prelic
 */
var FormElement = Element.extend({
	_init: function(selector) {
		this._super(selector);
	},

	getValue: function() {
		return $(this.getSelector()).val();
	},
	setValue: function(value) {
		$(this.getSelector()).val(value);
	}
});



/*
 * Ajax Class
 * By Marko Prelic
 */
var Ajax = Class.extend({
	_url: "",
	_type: "get",
	_dataType: "html",
	_data: [],
	_global: true,

	onBeforeSend: function() {},
	onSuccess: function() {},
	onError: function() {},
	onComplete: function() {},

	_init: function(url, type, dataType, data, global) {
		this._url = url;
		this._type = type;
		this._dataType = dataType;
		this._data = data;
		this._global = global;
	},

	sendRequest: function() {
		var caller = this;

		$.ajax({
			url: caller.getUrl(),
			type: caller.getType(),
			dataType: caller.getDataType(),
			data: caller.getData(),
			global: caller.getGlobal(),

			beforeSend: function() {
				return caller.onBeforeSend.apply(caller, arguments);
			},
			success: function() {
				caller.onSuccess.apply(caller, arguments);
			},
			error: function() {
				caller.onError.apply(caller, arguments);
			},
			complete: function() {
				caller.onComplete.apply(caller, arguments);
			},
		});
	},

	getUrl: function() {
		return this._url;
	},
	setUrl: function(url) {
		this._url = url;
	},

	getType: function() {
		return this._type;
	},
	setType: function(type) {
		this._type = type;
	},

	getDataType: function() {
		return this._dataType;
	},
	setDataType: function(dataType) {
		this._dataType = dataType;
	},

	getData: function() {
		return this._data;
	},
	setData: function(data) {
		this._data = data;
	},
	getGlobal: function() {
		return this._global;
	},
	setGlobal: function(global) {
		this._global = global;
	}
});
var EventEmitter = function () {
  this.events = {};
};

EventEmitter.prototype.on = function (event, listener) {
  if (typeof this.events[event] !== 'object') {
    this.events[event] = [];
  }

  this.events[event].push(listener);
};

EventEmitter.prototype.removeListener = function (event, listener) {
  var idx;

  if (typeof this.events[event] === 'object') {
    idx = indexOf(this.events[event], listener);

    if (idx > -1) {
      this.events[event].splice(idx, 1);
    }
  }
};

EventEmitter.prototype.emit = function (event) {
  var i, listeners, length, args = [].slice.call(arguments, 1);

  if (typeof this.events[event] === 'object') {
    listeners = this.events[event].slice();
    length = listeners.length;

    for (i = 0; i < length; i++) {
      listeners[i].apply(this, args);
    }
  }
};

EventEmitter.prototype.once = function (event, listener) {
  this.on(event, function g () {
    this.removeListener(event, g);
    listener.apply(this, arguments);
  });
};
