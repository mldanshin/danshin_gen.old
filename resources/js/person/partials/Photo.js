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
        switch(event.type) {
            case "change":
                this.#addItem(event.currentTarget);
                break;
            case "click":
                if (event.target.dataset.type !== "button-delete") {
                    return;
                }
                this.#deleteItem(event.currentTarget);
                break;
        }
    }

    #addEventListenerAdd()
    {
        let buttonAdd = document.getElementById("person-photo-add");
        if (buttonAdd) {
            buttonAdd.addEventListener("change", this);
        }
    }
    
    #addEventListenerDelete()
    {
        let containers = document.querySelectorAll("#person-photo-list .content-container");
        for (let item of containers) {
            item.addEventListener("click", this);
        }
    }

    async #addItem(inputFile)
    {
        this.#spinner.on();
        try {
            if (!inputFile.files[0]) {
                return;
            }

            let personId = document.getElementById("person-id").value;
            let url = inputFile.dataset.hrefPart;

            let formData = new FormData();
            formData.append("person_id", personId);
            formData.append("person_photo_file", inputFile.files[0]);
            
            let response = await this.#sender.sendRequest(url, {method: 'POST', body: formData});
            if (response && response.status === 200) {
                let text = await response.text();
                inputFile.parentNode.insertAdjacentHTML("beforebegin", text);
                this.#addEventListenerDelete();
            }
        } catch (error) {
            this.#toast.showErrorDefault();
            this.#sender.sendLog(error);
        } finally {
            inputFile.value = "";
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
