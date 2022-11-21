// Original JavaScript code by Chirp Internet: chirpinternet.eu
// Please acknowledge use of this code by including this header.

function AjaxRequestXML()
{
  "use strict";

  // public variables

  this.timeout = 4000;

  // private variables

  var req = false;
  var nocache = false;
  var initialHandler = false;
  var callbackTarget = false;
  var callbackParams = [];
  var timer = null;

  // define public methods

  this.post = function(AjaxTarget, params, callback)
  {
    if(callback) {
      this.setCallback(callback);
    }
    return this.loadXMLDoc("POST", AjaxTarget, params);
  };

  this.get = function(AjaxTarget, params, callback)
  {
    if(callback) {
      this.setCallback(callback);
    }
    if(nocache) {
      // add timestamp to make requests unique
      params.nocache = (new Date()).getTime();
    }
    return this.loadXMLDoc("GET", AjaxTarget, params);
  };

  this.loadXMLDoc = function(method, AjaxTarget, params)
  {
    var query = [];
    for(var x in params) {
      if(params[x] instanceof Array) {
        for(var y in params[x]) {
          query.push(x + "[]=" + encodeURIComponent(params[x][y]));
        }
      } else {
        query.push(x + "=" + encodeURIComponent(params[x]));
      }
    }
    query = query.join("&");
    try {
      req = new XMLHttpRequest();
      if(initialHandler) {
        req.onreadystatechange = initialHandler;
      } else {
        req.onreadystatechange = processReqChange;
      }

      switch(method)
      {
        case 'POST':

          // make Ajax request
          req.open(method, AjaxTarget, true);
          req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          req.send(query);

          // re-send in case of network failure
          timer = setTimeout(function() {
            req.abort();
            req.open(method, AjaxTarget, true);
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.send(query);
          }.bind(null, method, AjaxTarget, query), this.timeout);

          break;

        default:

          if(query) {
            AjaxTarget += "?" + query;
          }

          // make Ajax request
          req.open(method, AjaxTarget, true);
          req.send(null);

          // re-send in case of network failure
          timer = setTimeout(function() {
            req.abort();
            req.open(method, AjaxTarget, true);
            req.send(null);
          }.bind(null, method, AjaxTarget), this.timeout);

      }
      return true;
    } catch(e) {
      return false;
    }
  };

  this.nocache     = function() { nocache = true; };
  this.setHandler  = function(fn) { initialHandler = fn; };
  this.setCallback = function(fn) { callbackTarget = fn; };
  this.getResponse = function() { return req; };

  // define private methods

  var getNodeValue = function(parent, tagName)
  {
    var nodeList = parent.getElementsByTagName(tagName);
    if(nodeList.length > 1) {
      var params = [];
      for(var i=0; i < nodeList.length; i++) {
        if(nodeList[i].firstChild == null) { // empty
          params.push(null);
        } else {
          params.push(nodeList[i].firstChild.nodeValue);
        }
      }
      return params;
    } else if(nodeList.length == 1) {
      if(nodeList[0].firstChild == null) { // empty
        return "";
      } else {
        return nodeList[0].firstChild.nodeValue;
      }
    } else {
      return false;
    }
  };

  var processReqChange = function()
  {
    if(req.readyState != 4 || req.status != 200) {
      // data not ready
      return;
    }

    clearTimeout(timer);

    // received XML response
    if(req.responseXML == null) {
      console.log("Invalid XML response - please check the Ajax response data for invalid characters or formatting");
      return false;
    }

    var response  = req.responseXML.documentElement;
    var commands = response.getElementsByTagName("command");
    var target;
    var property;
    var value;
    var el;

    // process XML-embedded commands sequentially
    for(var i=0; i < commands.length; i++) {
      var method = commands[i].getAttribute("method");
      switch(method)
      {
        case "alert":
          var message = getNodeValue(commands[i], "message");
          window.alert(message);
          break;

        case "setvalue":
          target = getNodeValue(commands[i], "target");
          value = getNodeValue(commands[i], "value");
          if(target && value !== false) {
            el = document.getElementById(target);
            if(el) {
              el.value = value;
            } else {
              console.log("Cannot target missing element: " + target);
            }
          }
          break;

        case "setdefault":
          target = getNodeValue(commands[i], "target");
          if(target) {
            document.getElementById(target).value = document.getElementById(target).defaultValue;
          }
          break;

        case "focus":
          target = getNodeValue(commands[i], "target");
          if(target) {
            document.getElementById(target).focus();
          }
          break;

        case "setcontent":
          target = getNodeValue(commands[i], "target");
          var content = getNodeValue(commands[i], "content");
          var append = getNodeValue(commands[i], "append");
          if(target && (content !== false)) {
            el = document.getElementById(target);
            if(el) {
              if(append !== false) {
                var newcontent = document.createElement("div");
                newcontent.innerHTML = content;
                while(newcontent.firstChild) {
                  el.appendChild(newcontent.firstChild);
                }
              } else {
                el.innerHTML = content;
              }
            } else {
              console.log("Cannot target missing element: " + target);
            }
          }
          break;

        case "setstyle":
          target = getNodeValue(commands[i], "target");
          property = getNodeValue(commands[i], "property");
          value = getNodeValue(commands[i], "value");
          if(target && property && (value !== false)) {
            el = document.getElementById(target);
            if(el) {
              el.style[property] = value;
            }
          }
          break;

        case "setproperty":
        case "setdata":
          target = getNodeValue(commands[i], "target");
          property = getNodeValue(commands[i], "property");
          value = getNodeValue(commands[i], "value");
          if("true" == value) value = true;
          if("false" == value) value = false;
          if(target && document.getElementById(target)) {
            if("setdata" == method) {
              document.getElementById(target).dataset[property] = value;
            } else {
              document.getElementById(target)[property] = value;
            }
          }
          break;

        case "callback":
          callbackParams = getNodeValue(commands[i], "params");
          if(!Array.isArray(callbackParams)) {
            callbackParams = [callbackParams];
          }
          break;

        default:
          console.log("Unrecognised method '" + method + "' in processReqChange()");

      }

    } // foreach command

    if(callbackTarget) {
      // call callback function with callpack parameters
      callbackTarget.apply(null, callbackParams);
    }

  }; // loadXMLDoc

} // AjaxRequestXML