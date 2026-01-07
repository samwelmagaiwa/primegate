const HERO_TYPED_ELEMENT_ID = 'hero-typed-text';
const TYPE_SPEED_MS = 80;

class TypeWriter {
  private element: HTMLElement;
  private text: string;
  private index: number;

  constructor(element: HTMLElement, text: string) {
    this.element = element;
    this.text = text;
    this.index = 0;
    this.element.classList.add('typing-active');
    this.typeCharacter();
  }

  private typeCharacter(): void {
    if (this.index <= this.text.length) {
      this.element.textContent = this.text.slice(0, this.index);
      this.index += 1;
      window.setTimeout(() => this.typeCharacter(), TYPE_SPEED_MS);
    } else {
      this.element.classList.remove('typing-active');
      this.element.classList.add('typing-complete');
    }
  }
}

const initializeTypeWriter = (): void => {
  const element = document.getElementById(HERO_TYPED_ELEMENT_ID);
  if (!element) {
    return;
  }

  const datasetText = element.getAttribute('data-text');
  const contentText = element.textContent?.trim();
  const text = datasetText ?? contentText ?? '';

  if (!text) {
    return;
  }

  element.textContent = '';
  new TypeWriter(element, text);
};

document.addEventListener('DOMContentLoaded', initializeTypeWriter);
