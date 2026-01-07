const HERO_TYPED_ELEMENT_ID = "hero-typed-text";
const TYPE_SPEED_MS = 80;
const DELETE_SPEED_MS = 40;
const PAUSE_MS = 2000;

class TypeWriter {
  private element: HTMLElement;
  private text: string;
  private index: number;
  private isDeleting: boolean;

  constructor(element: HTMLElement, text: string) {
    this.element = element;
    this.text = text;
    this.index = 0;
    this.isDeleting = false;
    this.element.classList.add("typing-active");
    this.tick();
  }

  private tick(): void {
    const currentText = this.text;

    if (this.isDeleting) {
      this.element.textContent = currentText.slice(0, this.index);
      this.index -= 1;
    } else {
      this.element.textContent = currentText.slice(0, this.index);
      this.index += 1;
    }

    let delta = this.isDeleting ? DELETE_SPEED_MS : TYPE_SPEED_MS;

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

    window.setTimeout(() => this.tick(), delta);
  }
}

const initializeTypeWriter = (): void => {
  const element = document.getElementById(HERO_TYPED_ELEMENT_ID);
  if (!element) {
    return;
  }

  const datasetText = element.getAttribute("data-text");
  const contentText = element.textContent?.trim();
  const text = datasetText ?? contentText ?? "";

  if (!text) {
    return;
  }

  element.textContent = "";
  new TypeWriter(element, text);
};

document.addEventListener("DOMContentLoaded", initializeTypeWriter);
