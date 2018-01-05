function padZero(number) {
  return number < 10 ? "0" + number : number;
}

function updateClock(current, tmax, idPrefix) {
  if (current === 0) {
    for (var i = 0; i <= tmax; i++) {
      var tick = document.getElementById(idPrefix + padZero(i));
      tick.classList.remove('tick');
    }
  }  
  var tick = document.getElementById(idPrefix + padZero(current));
  tick.classList.add('tick');
}

function updateDate(today) {
  var weekDay = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
  var dateTemp = weekDay[today.getDay()] + '<br>' + 
  padZero(today.getDate()) + '-' + padZero(today.getMonth()+1) + '-' + today.getFullYear();
  var dack = document.getElementById('todayDate');
  dack.innerHTML = dateTemp;
}

function startClock() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();

  updateClock(s, 59, "s");
  if (s === 0) {
    updateClock(m, 59, "m");
    if (m === 0) {
      updateClock(h, 23, "h");
      if (h === 0) {
        updateDate(today);
      }
    }
  }
}

function initClock() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  var i = 0;
  var secondsEl = document.getElementById("seconds");
  var minutesEl = document.getElementById("minutes");
  var hoursEl = document.getElementById("hours");

  updateDate(today);
  
  for (i = 0; i <= 59; i++) {
    var tock = document.createElement("sec");
    tock.id = "s" + padZero(i);
    tock.style.webkitTransform = "rotate(" + i*6 + "deg)";
    tock.style.MozTransform = "rotate(" + i*6 + "deg)";
    tock.style.msTransform = "rotate(" + i*6 + "deg)";
    if (i <= s) {
      tock.classList.add("tick");
    }
    secondsEl.insertBefore(tock, null);
  }
  for (i = 0; i <= 59; i++) {
    var tock = document.createElement("min");
    tock.id = "m" + padZero(i);
    tock.style.webkitTransform = "rotate(" + i*6 + "deg)";
    tock.style.MozTransform = "rotate(" + i*6 + "deg)";
    tock.style.msTransform = "rotate(" + i*6 + "deg)";
    if (i <= m) {
      tock.classList.add("tick");
    }
    minutesEl.insertBefore(tock, null);    
  }      
  for (i = 0; i <= 23; i++) {
    var tock = document.createElement("hour");
    tock.id = "h"+ padZero(i);
    tock.style.webkitTransform = "rotate(" + i*15 + "deg)";
    tock.style.MozTransform = "rotate(" + i*15 + "deg)";
    tock.style.msTransform = "rotate(" + i*15 + "deg)";
    if (i <= h) {
      tock.classList.add("tick");
    }
    hoursEl.insertBefore(tock, null);
  }      

  setInterval(startClock, 500);
}  

window.onload = initClock;