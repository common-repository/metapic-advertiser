var tdconvObj = {};
var srcName = "tr_sdk.js?";
var queryString = getSrcQueryString(srcName);
var params = parseQuery(queryString);
var directLink = false;
tdconvObj.element = "iframe";
if (params.org) {
  tdconvObj.orgId = params.org;
}
if (params.dr) {
  tdconvObj.dr = true;
}
if (params.prog) {
  tdconvObj.program = true;
  tdconvObj.programId = params.prog;
}
if (tdconvObj.program == true && tdconvObj.dr == true) {
  loadRTag("rd-jssdk");
  window.rdAsyncInit = function () {
    RD.init({ merchant_id: tdconvObj.programId });
  };
}
if (document.readyState !== "loading") {
  if (getQueryString("deviceid")) {
    var tduid = getQueryString("deviceid");
  } else {
    var tduid = getQueryString("tduid");
  }
  processSetTduid(tduid);
  if (getQueryString("directLink")) {
    fireTDClk(tdconvObj.programId);
  }
} else {
  document.addEventListener("DOMContentLoaded", function () {
    if (getQueryString("deviceid")) {
      var e = getQueryString("deviceid");
    } else {
      var e = getQueryString("tduid");
    }
    processSetTduid(e);
    if (getQueryString("directLink")) {
      fireTDClk(tdconvObj.programId);
    }
  });
}
tdconvObj.tduid = getTduid("tduid");
function getSrcQueryString(e) {
  var t = document.getElementsByTagName("script");
  for (var r = 0; r < t.length; r++) {
    if (t[r].src.indexOf(e, 0) > 0) {
      return t[r].src.replace(/^[^\?]+\??/, "");
    }
  }
  return null;
}
function loadRTag(e) {
  if (document.getElementById(e)) {
    console.log(e + "exists");
    return;
  }
  var t = new Date();
  t.setMinutes(0);
  t.setSeconds(0);
  var r = parseInt(t.getTime() / 1e3);
  var n = "https://datar.tradedoubler.com/js/td-rd-o-sdk.js?t=" + r;
  var o = document.createElement("script");
  o.setAttribute("src", n);
  document.head.appendChild(o);
}
function parseQuery(e) {
  var t = new Object();
  if (!e) return t;
  var r = e.split(/[;&]/);
  for (var n = 0; n < r.length; n++) {
    var o = r[n].split("=");
    if (!o || o.length != 2) continue;
    var a = unescape(o[0]);
    var i = unescape(o[1]);
    i = i.replace(/\+/g, " ");
    t[a] = i;
  }
  return t;
}
function generateRandomOrderNumber() {
  return new Date().valueOf() + Math.random();
}
function getQueryString(t) {
  try {
    var e = new URLSearchParams(window.location.search);
    value = e.get(t);
  } catch (e) {
    try {
      value = getUrlParameter(t);
    } catch (e) {
      return false;
    }
  }
  return value;
}
function getTduid() {
  var e = "";
  if (getCookie("tduid")) {
    e = getCookie("tduid");
  } else if (getLocalStorage("tduid")) {
    e = getLocalStorage("tduid");
  }
  return e;
}
function getLocalStorage(e) {
  return window.localStorage.getItem(e);
}
function getCookie(e) {
  var t = e + "=";
  var r = document.cookie.split(";");
  for (var n = 0; n < r.length; n++) {
    try {
      var o = decodeURIComponent(r[n]);
    } catch (e) {
      if (checkDebug()) {
        console.log("Cannot URIDecode: " + r[n] + e.message);
      }
      continue;
    }
    while (o.charAt(0) == " ") {
      o = o.substring(1);
    }
    if (o.indexOf(t) == 0) {
      return o.substring(t.length, o.length);
    }
  }
  return false;
}
function getUrlParameter(e) {
  e = e.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var t = new RegExp("[\\?&]" + e + "=([^&#]*)");
  var r = t.exec(location.search);
  return r === null ? "" : decodeURIComponent(r[1].replace(/\+/g, " "));
}
function processSetTduid(e) {
  if (e != null) {
    try {
      setDomainCookie("tduid", e);
    } catch (e) {
      console.log(e);
    } finally {
      setCookie("tduid", e);
      setTduidLocalStorage(e);
    }
  } else {
    return false;
  }
}
function setCookie(e, t) {
  var r = new Date();
  r.setTime(r.getTime() + 365 * 24 * 60 * 60 * 1e3);
  var n = "expires=" + r.toUTCString();
  document.cookie = e + "=" + t + ";" + n + ";path=/;sameSite=none;Secure=true";
}
function setDomainCookie(e, t) {
  var r = window.location.host.substring(window.location.host.indexOf("."));
  if (r.match(/\./g).length < 2) {
    r = "." + window.location.host;
  } else if (r.substring(0, r.indexOf(".", 1)) == ".co") {
    r = "." + window.location.host;
  }
  var n = new Date();
  n.setTime(n.getTime() + 365 * 24 * 60 * 60 * 1e3);
  var o = "expires=" + n.toUTCString();
  document.cookie =
    e +
    "=" +
    t +
    ";" +
    o +
    ";domain=" +
    r +
    ";path=/;sameSite=none;Secure=true";
}
function setDebug(e) {
  var t = "Disabled";
  if (e == true) {
    t = "Enabled";
  }
  console.log("Tradedoubler Tracking SDK Debug Mode " + t + "!");
  window.localStorage.setItem("debug", e);
}
function checkDebug() {
  var e = false;
  if (getLocalStorage("debug")) {
    e = getLocalStorage("debug") === "true";
  }
  return e;
}
function getUrlParameter(e) {
  e = e.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var t = new RegExp("[\\?&]" + e + "=([^&#]*)");
  var r = t.exec(location.search);
  return r === null ? "" : decodeURIComponent(r[1].replace(/\+/g, " "));
}
var tduid;
function setTduidLocalStorage(e) {
  window.localStorage.setItem("tduid", e);
}
function fireTDTag(e, t, r, n) {
  if (r == true) {
    console.log(
      "Tradedoubler Tracking SDK Debug Mode! Element:" +
        n +
        "! Event: " +
        e +
        "! URL: " +
        t
    );
    return;
  } else {
    if (e == "lead" || e == "sale") {
      try {
        if (t.indexOf("tradedoubler.com") !== false) {
          var o = t.replace("tradedoubler.com", "pvnsolutions.com");
        } else if (t.indexOf("pvnsolutions.com") !== false) {
          var o = t;
        }
        if (typeof o !== "undefined") {
          navigator.sendBeacon(o);
        }
      } catch (e) {
        console.log("Send Beacon not working!" + e);
      }
    }
    var a = document.createElement(n);
    a.src = t + "&type=" + n;
    a.async = "yes";
    a.width = "1";
    a.height = "1";
    a.frameBorder = "0";
    document.body.appendChild(a);
    return;
  }
}
function fireTDClk(e) {
  if (getQueryString("prog")) {
    var t = getQueryString("prog");
  } else if (e) {
    var t = e;
  } else {
    console.log("No program Id set");
    return false;
  }
  if (getQueryString("aff")) {
    var r = getQueryString("aff");
  } else {
    console.log("No affiliate Id set");
    return false;
  }
  var n = "//clk.tradedoubler.com/click?p=" + t + "&a=" + r + "&f=0";
  var o = document.createElement("iframe");
  o.id = "tdClk";
  o.src = n;
  o.async = "yes";
  o.width = "1";
  o.height = "1";
  document.body.appendChild(o);
  return;
}
function processQueue(e) {
  if (e[0] == "init") {
    tdconvObj.debug = checkDebug();
    tdconvObj.orgId = e[1];
    if (e[2] != null) {
      if (e[2].element == "img") {
        tdconvObj.element = "img";
      }
      if (e[2].program) {
        tdconvObj.program = true;
        tdconvObj.programId = e[2].programId;
      } else {
        tdconvObj.program = false;
      }
    }
  }
  if (e[0] == "debug") {
    if (typeof e[1] === "boolean") {
      setDebug(e[1]);
      tdconvObj.debug = e[1];
    }
    return;
  }
  if (e[0] == "track") {
    trackEvent(e[1], e[2]);
    return;
  } else if (e[0] == "lp") {
    landingPage(e[1], e[2]);
    return;
  }
  return;
}
function validateExtType(e) {
  const t = /^[A-F0-1]{1}$/gi;
  if (t.test(e)) {
    return true;
  } else {
    console.warn(
      "The extType for Cross Device Tracking is not a valid, only 0 or 1 are valid values, please review: extType = " +
        e
    );
    return false;
  }
}
function validateExtIdHash(e) {
  const t = /^[A-F0-9]{64}$/gi;
  if (t.test(e)) {
    return true;
  } else {
    console.warn(
      "The extId for Cross Device Tracking is not a valid SHA-256 Hash, please review: " +
        e
    );
    return false;
  }
}
function validateValidOn(e) {
  const t = /^\d{4}\-\d{2}\-\d{2}$/;
  if (t.test(e)) {
    var r = new Date(e);
    if (r.getTime()) {
      console.log(
        "Comparing validOn: " + r.getTime() + "To now: " + new Date().getTime()
      );
      if (r.getTime() < new Date().getTime()) {
        console.warn("ValidOn date in past: " + e);
        return false;
      }
      return true;
    } else {
      console.warn("ValidOn not valid: " + e);
      return false;
    }
  } else {
    console.warn("ValidOn not valid: " + e);
    return false;
  }
}
function trackEvent(e, t) {
  element = "";
  if (tdconvObj.element == "iframe") {
    element = "&type=iframe";
  }
  if (e == "lead") {
    var r = generateRandomOrderNumber();
    var n = 4;
    if (typeof t.transactionId !== "undefined") {
      r = t.transactionId;
    }
    if (typeof t.event !== "undefined") {
      n = t.event;
    }
    //
    var o = "https://tbl.tradedoubler.com/report?";
    if (tdconvObj.program) {
      o = o + "program=" + tdconvObj.programId + "&";
    }
    o =
      o +
      "organization=" +
      tdconvObj.orgId +
      "&event=" +
      n +
      "&leadnumber=" +
      r +
      element +
      "&tduid=" +
      tdconvObj.tduid;
  } else if (e == "sale") {
    var a = generateRandomOrderNumber();
    var n = 5;
    var i = 0;
    var d = "";
    var c = "";
    var u = "";
    var l = "";
    var f = "";
    var s = "";
    if (typeof t.transactionId !== "undefined") {
      a = t.transactionId;
    }
    if (typeof t.event !== "undefined") {
      n = t.event;
    }
    if (typeof t.ordervalue !== "undefined") {
      i = t.ordervalue;
    }
    if (typeof t.currency !== "undefined") {
      d = "&currency=" + t.currency;
    }
    if (typeof t.voucher !== "undefined") {
      c = "&voucher=" + t.voucher;
    }
    if (typeof t.validOn !== "undefined") {
      if (validateValidOn(t.validOn)) {
        l = "&validOn=" + t.validOn;
      }
    }
    if (typeof t.cdt !== "undefined") {
      if (validateExtType(t.cdt.extType)) {
        if (validateExtIdHash(t.cdt.extId)) {
          if (typeof t.cdt.extId !== "undefined") {
            f = "&extid=" + t.cdt.extId;
          }
          if (typeof t.cdt.extType !== "undefined") {
            s = "&exttype=" + t.cdt.extType;
          }
        }
      }
    }
    if (typeof t.reportInfo !== "undefined") {
      u = "&reportInfo=" + t.reportInfo;
    }
    var o = "https://tbs.tradedoubler.com/report?";
    if (tdconvObj.program) {
      o = o + "program=" + tdconvObj.programId + "&";
    }
    o =
      o +
      "organization=" +
      tdconvObj.orgId +
      "&event=" +
      n +
      "&ordervalue=" +
      i +
      "&ordernumber=" +
      a +
      d +
      c +
      f +
      s +
      l +
      "&tduid=" +
      tdconvObj.tduid +
      u;
  }
  fireTDTag(e, o, tdconvObj.debug, tdconvObj.element);
}
var tdQueue = tdconv.q;
if (tdQueue) {
  tdQueue.forEach(function (e) {
    processQueue(e);
  });
}
var tdconv = function (e, t, r) {
  if (e == "init") {
    tdconvObj.orgId = t;
    tdconvObj.debug = checkDebug();
    if (r != null) {
      if (typeof r !== "object") {
        r = JSON.parse(r);
      }
      if (r.element == "img") {
        tdconvObj.element = "img";
        return;
      }
    }
  }
  if (e == "debug") {
    if (typeof t === "boolean") {
      setDebug(t);
      tdconvObj.debug = t;
    }
    return;
  }
  if (e == "track") {
    trackEvent(t, r);
    return;
  }
  return;
};
