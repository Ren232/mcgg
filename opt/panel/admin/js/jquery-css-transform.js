// Monkey patch jQuery 1.3.1+ to add support for setting or animating 2D and 3D CSS transforms.
// Richard Leggett www.richardleggett.co.uk (MIT license, see below)
// Forked from Zachary Johnson's jquery-animate-css.js/jquery-css-transform.js at http://github.com/zachstronaut/ 
// 
// Intro: 
// CSS3 supports a property known as "transform". The transform property has attributes for modifying the 3D transformation of an
// element using rotateX/rotateY(), translate3d() and perspective(), this file adds methods to jQuery to modify and animate these.
//
// Usage: 
// $(element).rotateX('70deg'); 
// $(element).perspective('150'); // lower = more pronounced	
// $(element).translate3d('50px', '0px', '0px'); 
// $(element).animate({rotateX:'50deg', rotateY:'180deg'}, {duration:1000}); 
// $(element).animate({translate3d:'50px, 20px, 32px'}, {duration:1000}); 
//
// More info: http://webkit.org/blog/386/3d-transforms/
//
// Copyright (c) 2010 Richard Leggett, Zachary Johnson  www.richardleggett.co.uk, www.zachstronaut.com
// Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
// to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
// and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
// The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
// WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

(function ($) {
	
	// these temporarily hold the units currently used with translate3d or rotateX/Y during animation
	var translateUnits = 'px';
	var rotateUnits = 'deg';


	var cssProxied = $.fn.css;
	$.fn.css = function (arg)
	{
		// Find the correct browser specific property and setup the mapping using
		// $.propNames which is used internally by jQuery.attr() when setting CSS
		// properties via either the css(name, value) or css(properties) method.
		// The problem with doing this once outside of css() method is that you
		// need a DOM node to find the right CSS property, and there is some risk
		// that somebody would call the css() method before body has loaded or any
		// DOM-is-ready events have fired.
		if
		(
			typeof $.props['perspective'] == 'undefined'
			&&
			(
				arg == 'perspective' 
				||
				(
					typeof arg == 'object'
					&& typeof arg['perspective'] != 'undefined'
				)
			)
		)
		{
			// add perspective style name mapping
			var _this = this;
			$.props['perspective'] = (function() { 
					// TODO: it's unclear wether the standard will be css prop "perspective", or "transform: perspective(N)" (Mozilla has no Z support)
					// for now Webkit only appears to respond to the former, hence...
					var properties = ['perspective', 'WebkitPerspective', 'MozPerspective'];
					var p;
					while (p = properties.shift()) {
						if (typeof _this.get(0).style[p] != 'undefined') {
							return p;
						}
					}
					return 'perspective'; // default 
				})();
		}
		
		if
        (
            typeof $.props['transform'] == 'undefined'
            &&
            (
                arg == 'transform'
                ||
                (
                    typeof arg == 'object'
                    && typeof arg['transform'] != 'undefined'
                )
            )
        )
        {
			// add transform style name mapping
			var _this = this;
            $.props['transform'] = (function() {
				 	// Try transform first for forward compatibility
			        var properties = ['transform', 'WebkitTransform', 'MozTransform'];
			        var p;
			        while (p = properties.shift())
			        {
			            if (typeof _this.get(0).style[p] != 'undefined') {
			                return p;
			            }
			        }
			        // Default to transform also
			        return 'transform';
				})();
        }
		
		// We force the property mapping here because jQuery.attr() does
		// property mapping with jQuery.propNames when setting a CSS property,
		// but curCSS() does *not* do property mapping when *getting* a
		// CSS property.  (It probably should since it manually does it
		// for 'float' now anyway... but that'd require more testing.)
		switch(arg) {
			case "perspective" : arg = $.props['perspective']; break;
			case "transform" : arg = $.props['transform']; break;
		}
		
		return cssProxied.apply(this, arguments);
	};
	
	
	// used by the rotateX and rotateY methods to actually apply/return the value of a transform CSS property
	// transform may look something like ".myStyle { transform: translate3d(40px, 40px, 0px), rotateY(45deg); }
	var applyRotate = function(prop, val)
	{
		var style = $(this).css('transform') || 'none';
	 
		// no params - return existing value
		if (typeof val == 'undefined') {
			if (style) {
				var m = style.match(new RegExp(prop+'\\(([^)]+)\\)'));
				if (m && m[1])
				{
					return m[1];
				}
			}

			return 0;
		}
		
		// otherwise modify current value
		var units = rotateUnits;
		var m = val.toString().match(/^(-?\d+(\.\d+)?)(.+)?$/);
		if (m) {
			if (m[3]) {
				units = m[3];
			}
			
			$(this).css( 
				'transform',
				style.replace(new RegExp('none|'+prop+'\\([^)]*\\)'), '') + prop + '(' + m[1] + units + ')'
			);
		}
	}
	
	$.fn.rotate = function (val)
    {
		return applyRotate.apply(this, ["rotate", val]);
    }

    $.fn.rotateX = function (val)
	{
		return applyRotate.apply(this, ["rotateX", val]);
	}
	
	$.fn.rotateY = function (val)
	{
		return applyRotate.apply(this, ["rotateY", val]);
	}
	
    // Note that scale is unitless.
    $.fn.scale = function (val, duration, options)
    {
        var style = $(this).css('transform');
        
        if (typeof val == 'undefined')
        {
            if (style)
            {
                var m = style.match(/scale\(([^)]+)\)/);
                if (m && m[1])
                {
                    return m[1];
                }
            }
            
            return 1;
        }
        
        $(this).css(
            'transform',
            style.replace(/none|scale\([^)]*\)/, '') + 'scale(' + val + ')'
        );
    }
	
	$.fn.perspective = function (val)
	{
		var style = $(this).css('perspective') || 'none';
	
		// no params - return existing value
		if (typeof val == 'undefined')
		{
			if (style) return style;
			return 0;
		}

		return $(this).css('perspective', val);
	}
	
	$.fn.translate3d = function (x, y, z)
	{
		var style = $(this).css('transform') || 'none';
		
		// no params - return existing value
		if (typeof x == 'undefined')
		{
			if (style)
			{
				var m = style.match(/translate3d\(([^)]+)\)/);
				if (m && m[1])
				{
					return m[1];
				}
			}
			return "0deg, 0deg, 0deg";
		}
		
		// otherwise modify current value
		return $(this).css( 
			'transform',
			style.replace(/none|translate3d\([^)]*\)/, '') + 'translate3d(' + x + ',' + y + ',' + z + ')'
		);
	}

 
	// fx.cur() must be monkey patched because otherwise it would always
	// return 0 for current transform values
	var curProxied = $.fx.prototype.cur;
	$.fx.prototype.cur = function ()
	{
		
		switch(this.prop) {
			case 'rotate' :			return parseFloat($(this.elem).rotate());	break; 
			case 'scale' :			return parseFloat($(this.elem).scale());	break; 
			case 'rotateX' :		return parseFloat($(this.elem).rotateX());	break; 
			case 'rotateY' :		return parseFloat($(this.elem).rotateY());	break; 
			case 'translate3d' :	return parseFloat($(this.elem).translate3d());	break; 
			case 'perspective' :	return parseFloat($(this.elem).perspective());	break; 
		}

   		//      else if (this.prop == 'scale')return parseFloat($(this.elem).scale());
   		// else if (this.prop == 'rotateY') return parseFloat($(this.elem).rotateY());
   		// else if (this.prop == 'rotateX') return parseFloat($(this.elem).rotateX());
   		// else if (this.prop == 'translate3d') return $(this.elem).translate3d();
   		// else if (this.prop == 'perspective') return parseFloat($(this.elem).perspective());
   		
		return curProxied.apply(this, arguments);
	}
	
	// step functions for animation
	
	$.fx.step.rotate = function (fx)
    {
        $(fx.elem).rotate(fx.now + rotateUnits);
    }
	
	$.fx.step.rotateY = function (fx)
	{
		$(fx.elem).rotateY(fx.now + rotateUnits);
	}
	
	$.fx.step.rotateX = function (fx)
	{
		$(fx.elem).rotateX(fx.now + rotateUnits);
	}
	
    $.fx.step.scale = function (fx)
    {
        $(fx.elem).scale(fx.now);
    }

	$.fx.step.perspective = function (fx)
	{
	 	$(fx.elem).perspective(fx.now);
	}
	
	$.fx.step.translateX = function (fx)
	{
		// TODO: This is pretty inefficient, consider .replace()
		var m = $(fx.elem).translate3d().match(/(-?\d+(\.\d+)?)/g);
		$(fx.elem).translate3d(fx.now + translateUnits, m[1] + translateUnits, m[2] + translateUnits);
	}
	
	$.fx.step.translateY = function (fx)
	{
		var m = $(fx.elem).translate3d().match(/(-?\d+(\.\d+)?)/g);
		$(fx.elem).translate3d(m[0] + translateUnits, fx.now + translateUnits, m[2] + translateUnits);
	}
	
	$.fx.step.translateZ = function (fx)
	{
		var m = $(fx.elem).translate3d().match(/(-?\d+(\.\d+)?)/g);
		$(fx.elem).translate3d(m[0] + translateUnits, m[1] + translateUnits, fx.now + translateUnits);
	}
	
	/*
    extend animate()
    Starting on line 3905 of jquery-1.3.2.js (5610 of 1.4.2) we have this code:
    
    // We need to compute starting value
    if ( unit != "px" ) {
        self.style[ name ] = (end || 1) + unit;
        start = ((end || 1) / e.cur(true)) * start;
        self.style[ name ] = start + unit;
    }
    
    This creates a problem where we cannot give units to our custom animation
    because if we do then this code will execute and because self.style[name]
    does not exist where name is our custom animation's name then e.cur(true)
    will likely return zero and create a divide by zero bug which will set
    start to NaN.
    
    The following monkey patch for animate() gets around this by storing the
    units used in the rotation definition and then stripping the units off.
    
    */
	var animateProxied = $.fn.animate;
	$.fn.animate = function (props) // see http://api.jquery.com/animate/
	{
		var propNames = [];
		
		if (typeof props['rotate'] != 'undefined') propNames.push('rotate');
		if (typeof props['rotateX'] != 'undefined') propNames.push('rotateX');
		if (typeof props['rotateY'] != 'undefined') propNames.push('rotateY');
		if (typeof props['perspective'] != 'undefined') propNames.push('perspective');
		if (typeof props['translate3d'] != 'undefined') propNames.push('translate3d');
		
		for(var i=0; i<propNames.length; i++) {
			switch(propNames[i]) {
				case 'rotate' :
				case 'rotateX' :
				case 'rotateY' :
					var m = props[propNames[i]].toString().match(/^(([+-]=)?(-?\d+(\.\d+)?))(.+)?$/);
					if (m && m[5]) {
						rotateUnits = m[5];
					}
					props[propNames[i]] = m[1];
					break;
					
				case 'perspective' :
					var m = props[propNames[i]].toString().match(/^(([+-]=)?(-?\d+(\.\d+)?))(.+)?$/);
					props[propNames[i]] = m[1];
					break;
					
				case 'translate3d' :
					var m = props[propNames[i]].toString().match(/(-?\d+(\.\d+)?)/g);
					props["translateX"] = m[0];
					props["translateY"] = m[1];
					props["translateZ"] = m[2];
					break;
			}
		}
		
		return animateProxied.apply(this, arguments);
	}
})(jQuery);