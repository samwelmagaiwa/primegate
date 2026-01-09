(function(){
  "use strict";

  function createLogoBand(documentRef){
    var band = documentRef.createElement("div");
    band.className = "global-logo-band";

    var link = documentRef.createElement("a");
    link.className = "global-logo-link";
    link.href = "index-2.html";
    link.setAttribute("aria-label", "Primegate International Home");

    var img = documentRef.createElement("img");
    img.src = "upload/logo-removebg-preview.png";
    img.alt = "Primegate International Logo";
    img.loading = "lazy";
    link.appendChild(img);

    var callout = documentRef.createElement("div");
    callout.className = "header-innovation-callout";
    callout.setAttribute("aria-label", "Innovation Spotlight");

    var badge = documentRef.createElement("span");
    badge.className = "innovation-badge";
    badge.textContent = "Innovation";

    var text = documentRef.createElement("span");
    text.className = "innovation-text";
    text.textContent = "Smart gateway solutions for Africa";

    callout.appendChild(badge);
    callout.appendChild(text);

    band.appendChild(link);
    band.appendChild(callout);

    return band;
  }

  function insertBand(){
    var bandAlready = document.querySelector(".global-logo-band");
    if(bandAlready) return;

    var headerWrapper = document.querySelector(".logisco-header-wrap");
    if(!headerWrapper) return;

    var band = createLogoBand(document);
    var parent = headerWrapper.parentNode;
    if(parent){
      parent.insertBefore(band, headerWrapper.nextSibling);
    }
  }

  if(document.readyState === "complete" || document.readyState === "interactive"){
    setTimeout(insertBand, 0);
  } else {
    document.addEventListener("DOMContentLoaded", insertBand);
  }
})();
