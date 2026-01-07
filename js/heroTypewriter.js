"use strict";
var HERO_TYPED_ELEMENT_ID = "hero-typed-text";
var TYPE_SPEED_MS = 80;
var DELETE_SPEED_MS = 40;
var PAUSE_MS = 2000;

var TypeWriter = /** @class */ (function () {
  function TypeWriter(element, text) {
    this.element = element;
    this.text = text;
    this.index = 0;
    this.isDeleting = false;
    this.element.classList.add("typing-active");
    this.tick();
  }
  TypeWriter.prototype.tick = function () {
    var _this = this;
    var currentText = this.text;
    if (this.isDeleting) {
      this.element.textContent = currentText.slice(0, this.index);
      this.index -= 1;
    } else {
      this.element.textContent = currentText.slice(0, this.index);
      this.index += 1;
    }
    var delta = this.isDeleting ? DELETE_SPEED_MS : TYPE_SPEED_MS;
    if (!this.isDeleting && this.index > currentText.length) {
      this.isDeleting = true;
      delta = PAUSE_MS;
      this.element.classList.remove("typing-active");
      this.element.classList.add("typing-complete");
    } else if (this.isDeleting && this.index < 0) {
      this.isDeleting = false;
      this.index = 0;
      delta = 500;
      this.element.classList.add("typing-active");
      this.element.classList.remove("typing-complete");
    }
    window.setTimeout(function () {
      return _this.tick();
    }, delta);
  };
  return TypeWriter;
})();

var initializeTypeWriter = function () {
  var _a;
  var element = document.getElementById(HERO_TYPED_ELEMENT_ID);
  if (!element) {
    return;
  }
  var datasetText = element.getAttribute("data-text");
  var contentText =
    (_a = element.textContent) === null || _a === void 0 ? void 0 : _a.trim();
  var text =
    datasetText !== null && datasetText !== void 0
      ? datasetText
      : contentText !== null && contentText !== void 0
      ? contentText
      : "";
  if (!text) {
    return;
  }
  element.textContent = "";
  new TypeWriter(element, text);
};
document.addEventListener("DOMContentLoaded", initializeTypeWriter);
