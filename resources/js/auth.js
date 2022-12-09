"use strict";

startTimerCodeActual();
startTimerCodeRepeated();

function startTimerCodeActual()
{
    let time = document.getElementById("auth-confirmation-time");
    if (time) {
        let func = function () {
            time.innerHTML = (time.textContent * 1) - 1;
        };
        let timerId = setInterval(func, 1000);
    
        let stopTimer = function() {
            clearInterval(timerId);
            window.location.href = time.dataset.href;
        };
        setTimeout(stopTimer, time.textContent * 1000);
    }
}

function startTimerCodeRepeated()
{
    let container = document.getElementById("auth-confirmation-repeated-time-container");
    let time = document.getElementById("auth-confirmation-repeated-time");
    let button = document.getElementById("auth-confirmation-repeated-button");
    if (container && time && button) {
        let func = function () {
            let value = Number(time.textContent);
            if (value > 0) {
                button.style.display = "none";
                container.style.display = "block";
            } else {
                button.style.display = "block";
                container.style.display = "none";
                return;
            }

            time.innerHTML = value - 1;
        };
        let timerId = setInterval(func, 1000);
    
        let stopTimer = function() {
            clearInterval(timerId);
            button.style.display = "block";
            container.style.display = "none";
        };
        setTimeout(stopTimer, Number(time.textContent) * 1000);
    }
}
