export default class ListInputComponent
{
    #sender;
    #spinner;
    #toast;

    /**
     * @param {object} sender 
     * @param {object} spinner 
     * @param {object} toast 
     */
    constructor(sender, spinner, toast)
    {
        this.#sender = sender;
        this.#spinner = spinner;
        this.#toast = toast;
        this.#addEventListenerAdd();
        this.#addEventListenerDelete();
    }

    handleEvent(event) {
        let currentTarget = event.currentTarget;
        if (currentTarget.classList.contains("button-add")) {
            this.#addItem(currentTarget);
        } else {
            if (event.target.dataset.type !== "button-delete") {
                return;
            }
            this.#deleteItem(currentTarget);
        }
    }

    #addEventListenerAdd()
    {
        let buttonsAdd = document.querySelectorAll(".list-input-container .button-add");
        for (let item of buttonsAdd) {
            item.addEventListener("click", this);
        }
    }

    #addEventListenerDelete()
    {
        let containers = document.querySelectorAll(".list-input-container .content-container");
        for (let item of containers) {
            item.addEventListener("click", this);
        }
    }

    async #addItem(button)
    {
        this.#spinner.on();

        try {
            let url = button.dataset.hrefPart;
        
            let response = await this.#sender.sendRequest(url);
            if (response && response.status === 200) {
                let text = await response.text();
                button.insertAdjacentHTML("beforebegin", text);
                this.#addEventListenerDelete();
            }
        } catch (error) {
            this.#toast.showErrorDefault();
            this.#sender.sendLog(error);
        } finally {
            this.#spinner.off();
        }
    }

    #deleteItem(container)
    {
        try {
            container.remove();
        } catch (error) {
            this.#toast.showErrorDefault();
            this.#sender.sendLog(error);
        }
    }
}
