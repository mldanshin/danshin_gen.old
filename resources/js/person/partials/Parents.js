export default class Photo
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
        if (currentTarget.id == "person-parents-add") {
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
        let buttonAdd = document.getElementById("person-parents-add");
        if (buttonAdd) {
            buttonAdd.addEventListener("click", this);
        }
    }

    #addEventListenerDelete()
    {
        let containers = document.querySelectorAll("#person-parents .content-container");
        for (let item of containers) {
            item.addEventListener("click", this);
        }
    }

    async #addItem(button)
    {
        this.#spinner.on();

        try {
            let personId = document.getElementById("person-id").value;
            let typeParentId = document.getElementById("person-parents-type-sample").value;
            let url = button.dataset.hrefPart;

            let formData = new FormData();
            formData.append("person_id", personId);
            formData.append("parent_role", typeParentId);

            let response = await this.#sender.sendRequest(url, {method: 'POST', body: formData});
            if (response && response.status === 200) {
                let text = await response.text();
                let paranet = button.parentNode;
                paranet.insertAdjacentHTML("beforebegin", text);
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
