import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["collectionContainer"]

    static values = {
        index: Number,
        prototype: String,
    }

    connect() {
        super.connect();

        document
            .querySelectorAll('ul.tags li')
            .forEach((item) => {
                this.addDeleteLink(item);
            })
    }

    addCollectionElement(event) {
        const item = document.createElement('li');
        item.classList.add('mb-3');
        item.innerHTML = this.htmlDecode(this.prototypeValue.replace(/__name__/g, this.indexValue));
        this.addDeleteLink(item);
        this.collectionContainerTarget.appendChild(item);
        this.indexValue++;
    }

    addDeleteLink(item) {
        const removeFormButton = document.createElement('button');
        removeFormButton.innerText = 'Удалить';
        removeFormButton.classList.add('btn', 'btn-danger');

        item.append(removeFormButton);

        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            // remove the li for the tag form
            item.remove();
        });
    }

    htmlDecode(input) {
        const doc = new DOMParser().parseFromString(input, "text/html");
        return doc.documentElement.textContent;
    }
}