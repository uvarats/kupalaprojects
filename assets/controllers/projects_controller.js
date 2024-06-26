import {Controller} from '@hotwired/stimulus';
import {Tooltip} from "bootstrap";

export default class extends Controller {
    connect() {
        document.addEventListener("DOMContentLoaded", function () {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl))
        });
    }
}
