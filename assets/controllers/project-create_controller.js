import {Controller} from "@hotwired/stimulus";
import axios from "axios";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['endsAt', 'startsAt', 'festivalSelect'];
    static values = {
        datesEndpoint: String,
    };

    festivalDates;

    connect() {
        super.connect();

        axios.post(this.datesEndpointValue)
            .then((response) => {
                this.festivalDates = response.data;
            })
            .then(() => {
                this.updateDates();
            });
    }

    festivalSelection(event) {
        this.updateDates();
    }

    updateDates() {
        const festivalId = this.festivalSelectTarget.value;
        const dates = this.festivalDates.find(f => f.festivalId === festivalId);

        this.startsAtTarget.value = dates.startsAt;
        this.endsAtTarget.value = dates.endsAt;
    }
}