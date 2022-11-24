import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        this.element.innerHTML = 'Hello Stimulus! Edit me in assets/controllers/home_controller.js';
    }
}
