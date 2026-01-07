"use strict";
var HERO_TYPED_ELEMENT_ID = 'hero-typed-text';
var TYPE_SPEED_MS = 80;
var TypeWriter = /** @class */ (function () {
    function TypeWriter(element, text) {
        this.element = element;
        this.text = text;
        this.index = 0;
        this.element.classList.add('typing-active');
        this.typeCharacter();
    }
    TypeWriter.prototype.typeCharacter = function () {
        var _this = this;
        if (this.index <= this.text.length) {
            this.element.textContent = this.text.slice(0, this.index);
            this.index += 1;
            window.setTimeout(function () { return _this.typeCharacter(); }, TYPE_SPEED_MS);
        }
        else {
            this.element.classList.remove('typing-active');
            this.element.classList.add('typing-complete');
        }
    };
    return TypeWriter;
}());
var initializeTypeWriter = function () {
    var _a;
    var element = document.getElementById(HERO_TYPED_ELEMENT_ID);
    if (!element) {
        return;
    }
    var datasetText = element.getAttribute('data-text');
    var contentText = (_a = element.textContent) === null || _a === void 0 ? void 0 : _a.trim();
    var text = datasetText !== null && datasetText !== void 0 ? datasetText : contentText !== null && contentText !== void 0 ? contentText : '';
    if (!text) {
        return;
    }
    element.textContent = '';
    new TypeWriter(element, text);
};
document.addEventListener('DOMContentLoaded', initializeTypeWriter);
