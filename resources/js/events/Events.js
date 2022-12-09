"use strict";

import events from '../Events.js';
import layout from '../Layout.js';
import peopleFilterOrdering from '../people/FilterOrdering.js';
import sender from '../Sender.js';
import spinner from "../Spinner.js";
import Subscription from './Subscription.js';
import toast from '../Toast.js';

class Events
{
    #events;
    #layout;
    #peopleFilterOrdering;
    #sender;
    #spinner;
    #toast;

    constructor(events, layout, peopleFilterOrdering, sender, spinner, toast)
    {
        this.#events = events;
        this.#layout = layout;
        this.#peopleFilterOrdering = peopleFilterOrdering;
        this.#sender = sender;
        this.#spinner = spinner;
        this.#toast = toast;

        let button = document.getElementById("events-button");
        button.addEventListener("click", this);

        this.#addEventListenerButtonPersonShow();

        Subscription.getInstance();
    }

    async #showByButton(button)
    {
        this.#spinner.on();
        try {
            let url = button.dataset.href;
            let urlPart = button.dataset.hrefPart;
            await this.show(urlPart);
            history.pushState({url: urlPart}, null, url + this.#peopleFilterOrdering.getRequest());
        } catch (error) {
            this.#sender.sendLog(error);
            this.#toast.showErrorDefault();
        } finally {
            this.#spinner.off();
        }
    }

    /**
     * 
     * @param {string} url 
     */
    async show(url)
    {
        let response = await this.#sender.sendRequest(url);
        if (response && response.status === 200) {
            this.#layout.mainContainer.innerHTML = await response.text();
            this.#addEventListenerButtonPersonShow();
            Subscription.getInstance();
        }
    }

    handleEvent(event) {
        let elem = event.currentTarget;
        switch (event.type) {
            case "click":
                switch (elem.id) {
                    case "events-button":
                        this.#showByButton(elem);
                        break;
                }
                break;
        }

        if (elem.classList.contains("event__button-show-person")) {
            event.currentTarget.dispatchEvent(this.#events.pressShowPerson);
        }
    }

    #addEventListenerButtonPersonShow()
    {
        let buttons = document.querySelectorAll(".event__button-show-person");
        for (let button of buttons) {
            button.addEventListener("click", this);
        }
    }
}

export default new Events(events, layout, peopleFilterOrdering, sender, spinner, toast);
