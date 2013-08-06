/*
  $Id:toc.js $ 
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

jQuery.Toc = jQuery.Toc || {};

/**
 * Copies all the properties of config to obj.
 * @param {Object} obj The receiver of the properties
 * @param {Object} config The source of the properties
 * @param {Object} defaults A different object that will also be applied for default values
 * @return {Object} returns obj
 * @member Ext apply
 */

jQuery.Toc.apply = function(o, c, defaults){
    // no "this" reference for friendly out of scope calls
    if(defaults){
    	jQuery.Toc.apply(o, defaults);
    }
    if(o && c && typeof c == 'object'){
        for(var p in c){
            o[p] = c[p];
        }
    }
    
    return o;
};

(function() {
  var toString = Object.prototype.toString,
  ua = navigator.userAgent.toLowerCase(),
  check = function(r){
   	return r.test(ua);
  },
  isOpera = check(/opera/),
  isIE = !isOpera && check(/msie/);

	jQuery.Toc.apply(jQuery.Toc, {
		/* @param {Object} origclass The class to override
     * @param {Object} overrides The list of functions to add to origClass.  This should be specified as an object literal
     * containing one or more methods.
     * @method override
     */
    override : function(origclass, overrides){
      if(overrides){
        var p = origclass.prototype;
        jQuery.Toc.apply(p, overrides);
        
        if(jQuery.Toc.isIE && overrides.toString != origclass.toString){
            p.toString = overrides.toString;
        }
      }
    },
	 
		/* @param {Function} subclass The class inheriting the functionality
    * @param {Function} superclass The class being extended
    * @param {Object} overrides (optional) A literal with members which are copied into the subclass's
    * prototype, and are therefore shared between all instances of the new class.
    * @return {Function} The subclass constructor.
    * @method extend
    */
    extend : function(){
       // inline overrides
       var io = function(o){
           for(var m in o){
               this[m] = o[m];
           }
       };
       var oc = Object.prototype.constructor;
      
       return function(sb, sp, overrides){
         if(jQuery.Toc.isObject(sp)){
             overrides = sp;
             sp = sb;
             sb = overrides.constructor != oc ? overrides.constructor : function(){sp.apply(this, arguments);};
         }
         var F = function(){},
             sbp,
             spp = sp.prototype;
      
         F.prototype = spp;
         sbp = sb.prototype = new F();
         sbp.constructor=sb;
         sb.superclass=spp;
         if(spp.constructor == oc){
             spp.constructor=sp;
         }
         sb.override = function(o){
         	 	jQuery.Toc.override(sb, o);
         };
         sbp.superclass = sbp.supr = (function(){
             return spp;
         });
         sbp.override = io;
         jQuery.Toc.override(sb, overrides);
         sb.extend = function(o){jQuery.Toc.extend(sb, o);};
         return sb;
       };
    }(),
   
    /**
    * Returns true if the passed object is a JavaScript Object, otherwise false.
    * @param {Object} object The object to test
    * @return {Boolean}
    */
    isObject : function(v){
      return v && typeof v == "object";
    },
    
    /**
    * True if the detected browser is Internet Explorer.
    * @type Boolean
    */
    isIE : isIE
  });
})();

