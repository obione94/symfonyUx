import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['eventtg'];

    connect() {
        this.count = 0;
    }

    increment() {
        this.count++;
        this.eventtgTarget.innerText = this.count;
    }
}
