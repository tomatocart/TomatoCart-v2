/** 
 * $Id: Format.php $
 * TomatoCart Open Source Shopping Cart Solutions
 * http://www.tomatocart.com

 * Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v2 (1991)
 * as published by the Free Software Foundation.
 */


Ext.util.Format.CurrencyFactory = function(dp, dSeparator, tSeparator, leftSymbol, rightSymbol) {
  return function(n) {
    dp = Math.abs(dp) + 1 ? dp : 2;
    dSeparator = dSeparator || ".";
    tSeparator = tSeparator || ",";
    rightSymbol = rightSymbol || null;

    var m = /(\d+)(?:(\.\d+)|)/.exec(n + ""),
        x = m[1].length > 3 ? m[1].length % 3 : 0;

    return leftSymbol + " "
           + (n < 0? '-' : '') // preserve minus sign
           + (x ? m[1].substr(0, x) + tSeparator : "")
           + m[1].substr(x).replace(/(\d{3})(?=\d)/g, "$1" + tSeparator)
           + (dp? dSeparator + (+m[2] || 0).toFixed(dp).substr(2) : "")
           + ((rightSymbol != null) ? (" " + rightSymbol) : '');
  };
};