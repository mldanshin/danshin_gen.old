import clipboard from '../Clipboard.js';
import Messages from "../Messages.js";
import layout from '../Layout.js';
import peopleFilterOrdering from '../people/FilterOrdering.js';
import sender from '../Sender.js';
import spinner from "../Spinner.js";
import toast from '../Toast';

export default class Subscription
{
    #clipboard;
    #messages;
    #layout;
    #peopleFilterOrdering;
    #sender;
    #spinner;
    #toast;
    #eventExit;

    constructor(clipboard, messages, layout, peopleFilterOrdering, sender, spinner, toast)
    {
        this.#clipboard = clipboard;
        this.#messages = messages;
        this.#layout = layout;
        this.#peopleFilterOrdering = peopleFilterOrdering;
        this.#sender = sender;
        this.#spinner = spinner;
        this.#toast = toast;

        this.#initializeCreateButton();
        this.#initializeStoreForm();
        this.#initializeButtonsCopy();
        this.#initializeDeleteForm();

        this.#eventExit = new CustomEvent("exitSubscription", {bubbles: true});
    }

    static getInstance()
    {
        return new Subscription(clipboard, new Messages(), layout, peopleFilterOrdering, sender, spinner, toast);
    }

    handleEvent(event) {
        let elem = event.currentTarget;
        switch (event.type) {
            case "click":
                switch (elem.id) {
                    case "events-subscription-create-button":
                        this.#create(elem);
                        break;
                    case "events-subscription-create-button-copy-code":
                        this.#copyCode();
                        break;
                    case "events-subscription-create-button-copy-botname":
                        this.#copyBotName();
                        break;
                }
                break;
            case "submit":
                switch (elem.id) {
                    case "events-subscription-store-form":
                        this.#store(event);
                        break;
                    case "events-subscription-delete-form":
                        this.#deleteSubscription(event);
                        break;
                }
        }
    }

    #initializeCreateButton()
    {
        let createButton = document.getElementById("events-subscription-create-button");
        if (createButton) {
            createButton.addEventListener("click", this);
        }
    }

    #initializeStoreForm()
    {
        let storeForm = document.getElementById("events-subscription-store-form");
        if (storeForm) {
            storeForm.addEventListener("submit", this);
        }
    }

    #initializeButtonsCopy()
    {
        let clipboard = navigator.clipboard;
        
        let code = document.getElementById("events-subscription-create-button-copy-code");
        if (code) {
            if (clipboard) {
                code.addEventListener("click", this);
            } else {
                code.classList.add("hidden");
            }
        }

        let botName = document.getElementById("events-subscription-create-button-copy-botname");
        if (botName) {
            if (clipboard) {
                botName.addEventListener("click", this);
            } else {
                botName.classList.add("hidden");
            }
        }
    }

    #initializeDeleteForm()
    {
        let deleteForm = document.getElementById("events-subscription-delete-form");
        if (deleteForm) {
            deleteForm.addEventListener("submit", this);
        }
    }

    async #create(button)
    {
        this.#spinner.on();
        try {
            let url = button.dataset.href;
            let urlPart = button.dataset.hrefPart;
            let response = await this.#sender.sendRequest(urlPart);
            this.#layout.mainContainer.innerHTML = await response.text();
            this.#initializeStoreForm();
            this.#initializeButtonsCopy();
            history.pushState({url: urlPart}, null, url + this.#peopleFilterOrdering.getRequest());
        } catch (error) {
            this.#toast.showErrorDefault();
            this.#sender.sendLog(error);
        } finally {
            this.#spinner.off();
        }
    }

    async #store(event)
    {
        this.#spinner.on();
        event.preventDefault();
        try {
            let form = event.currentTarget;
            let response = await this.#sender.sendRequest(form.action, {method: 'POST', body: new FormData(form)});
            let json = await response.json();
            if (json.status === 1) {
                this.#toast.show(json.message);
                let container = document.getElementById("events-subscription-create");
                container.dispatchEvent(this.#eventExit);
            } else {
                alert(json.message);
            }
        } catch (error) {
            this.#toast.showErrorDefault();
            this.#sender.sendLog(error);
        } finally {
            this.#spinner.off();
        }
    }

    async #deleteSubscription(event)
    {
        event.preventDefault();
        if (confirm(this.#messages.eventSubscriptionDeleteConfirmation)) {
            this.#spinner.on();
            try {
                let form = event.currentTarget;
                let response = await this.#sender.sendRequest(form.action, {method: 'POST', body: new FormData(form)});
                let json = await response.json();
                if (json.status === 1) {
                    this.#toast.show(json.message);
                    let container = document.getElementById("subscription-control-container");
                    container.dispatchEvent(this.#eventExit);
                } else {
                    throw new Error("Error delete.");
                }
            } catch (error) {
                this.#toast.showErrorDefault();
                this.#sender.sendLog(error);
            } finally {
                this.#spinner.off();
            }
        }
    }

    #copyCode()
    {
        this.#clipboard.copy("events-subscription-code");
    }

    #copyBotName()
    {
        this.#clipboard.copy("events-subscription-botname");
    }
}
