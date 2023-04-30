import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        updateUrl: String,
        removeUrl: String,
    }

    update(event) {
        window.location = this.updateUrlValue;
    }

    remove(event) {
        let accepted= confirm('Вы действительно хотите удалить данный фестиваль?');

        if (accepted) {
            window.location = this.removeUrlValue;
        }
    }
}
