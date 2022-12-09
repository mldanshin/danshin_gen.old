import eventsObject from '../Events.js';
import filterOrderingObject from './FilterOrdering.js';
import senderObject from '../Sender.js';
import spinnerObject from "../Spinner.js";
import toastObject from '../Toast.js';

class Collection
{
    #events;
    #sender;
    #spinner;
    #toast;
    #collectionContainer;
    #filterOrdering;

    constructor(events, sender, spinner, toast, filterOrdering)
    {
        this.#events= events;
        this.#sender= sender;
        this.#spinner= spinner;
        this.#toast= toast;
        this.#filterOrdering= filterOrdering;

        this.#collectionContainer = document.getElementById("people-collection-container");

        document.body.addEventListener("changeFilterOrdering", this);
        document.body.addEventListener("personChange", this);
        this.#addEventListenerButtonsShowPerson();
        this.#addEventListenerButtonsShowTree();
    }

    get buttonsShowPerson()
    {
        return document.querySelectorAll(".people__button-show-person");
    }

    get buttonsShowTree()
    {
        return document.querySelectorAll(".people__button-show-tree");
    }

    /**
     * @param {string} searchText
     * @param {number} orderNumber
     */
    async update(searchText, orderNumber) {
        await this.updateCollectionBy(searchText, orderNumber);
        this.#filterOrdering.setValueSearch(searchText);
        this.#filterOrdering.setValueOrderGroup(orderNumber);
    }

    async updateCollection()
    {
        this.#spinner.on(false);
        try {
            await this.updateCollectionBy(this.#filterOrdering.getValueSearch(), this.#filterOrdering.getValueOrderGroup());
        } catch (error) {
            this.#toast.showErrorDefault();
            this.#sender.sendLog(error);
        } finally {
            this.#spinner.off();
        }
    }

    /**
     * @param {string} searchText
     * @param {number} orderNumber
     */
    async updateCollectionBy(searchText, orderNumber)
    {
        let formData = new FormData();
        formData.append("people_search", searchText);
        formData.append("people_order", orderNumber);

        let response = await this.#sender.sendRequest(
            this.#filterOrdering.url,
            {method: 'POST', body: formData}
            );
        if (response && response.status === 200) {
            this.#collectionContainer.innerHTML = await response.text();
            this.#addEventListenerButtonsShowPerson();
            this.#addEventListenerButtonsShowTree();
        }
    }

    handleEvent(event) {
        switch (event.type) {
            case "changeFilterOrdering":
                this.updateCollection();
                break;
            case "personChange":
                this.updateCollection();
                break;
            case "click":
                let elem = event.currentTarget;
                if (elem.classList.contains("people__button-show-person")) {
                    elem.dispatchEvent(this.#events.pressShowPerson);
                } else if (elem.classList.contains("people__button-show-tree")) {
                    elem.dispatchEvent(this.#events.pressShowTree);
                }
                break;
        }
    }

    #addEventListenerButtonsShowPerson()
    {
        for (let item of this.buttonsShowPerson) {
            item.addEventListener("click", this);
        }
    }

    #addEventListenerButtonsShowTree()
    {
        for (let item of this.buttonsShowTree) {
            item.addEventListener("click", this);
        }
    }
}

export default new Collection(
    eventsObject,
    senderObject,
    spinnerObject,
    toastObject,
    filterOrderingObject
);
