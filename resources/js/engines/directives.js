Directives = {};
Directives._list = [];
Directives.register = function(name, fnAction) {
	this._list[name] = fnAction;
}

Directives.apply = function(element, name) {
	var directiveFn = this._list[name];
	if (directiveFn === undefined) {
		throw 'Undefined \'' + name + '\' directive';
	}
	directiveFn(element);
}

Directives.applyAll = function(selector) {
	var e = $(selector)[0]; var name;
	if (e == undefined) { return; }
	for(var i=0;i<e.attributes.length;i++) {
		name = e.attributes[i].name;
		if (this._list[name]!==undefined) {
			this.apply(e, name);
		}
	}
}

Directives.findNApplyAll = function (selector, directives) {
	var directives = (directives||[]);
	for (var i in directives) {
		directives[i]='['+directives[i]+']';
	}
	var $c = $(selector).find('*[webos]' + directives.join(''));
	for(var i = 0; i<$c.length;i++) {
		this.applyAll($c[i]);
	}
}

Directives.getObjectId = function(el) {
	return $(el).attr('id') || $(el).parents('[id]').attr('id');
}