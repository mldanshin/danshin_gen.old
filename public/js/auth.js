(()=>{"use strict";!function(){var t=document.getElementById("auth-confirmation-time");if(t){var e=setInterval((function(){t.innerHTML=1*t.textContent-1}),1e3);setTimeout((function(){clearInterval(e),window.location.href=t.dataset.href}),1e3*t.textContent)}}(),function(){var t=document.getElementById("auth-confirmation-repeated-time-container"),e=document.getElementById("auth-confirmation-repeated-time"),n=document.getElementById("auth-confirmation-repeated-button");if(t&&e&&n){var o=setInterval((function(){var o=Number(e.textContent);if(!(o>0))return n.style.display="block",void(t.style.display="none");n.style.display="none",t.style.display="block",e.innerHTML=o-1}),1e3);setTimeout((function(){clearInterval(o),n.style.display="block",t.style.display="none"}),1e3*Number(e.textContent))}}()})();