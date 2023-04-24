import { Controller } from '@hotwired/stimulus';
import axios from "axios";

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['name', 'startDate', 'endDate'];

    static values = {
        generateNameUrl: String,
    }

    generateCheck(event) {
        let target = event.target;

        if (target.checked) {
            this.nameTarget.value = '';
        }

        this.nameTarget.disabled = target.checked;
    }
}
