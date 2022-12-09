import collectionObject from './Collection.js';
import layoutObject from '../Layout.js';

class Container
{
    #mediaScreenMaxWidth = 650;
    #layout;
    #collection;
    #peopleContainer;

    /**
     * 
     * @param {object} layout 
     * @param {object} collection 
     */
    constructor(layout, collection)
    {
        this.#layout= layout;
        this.#collection= collection;

        let dropdownButton = document.getElementById("people-dropdown-button");
        this.#peopleContainer = document.getElementById("people-container");
        let buttonClose = document.getElementById("people-button-close");

        window.addEventListener("resize", this)
        document.addEventListener("DOMContentLoaded", this);
        dropdownButton.addEventListener("click", this);
        buttonClose.addEventListener("click", this);
        this.#peopleContainer.addEventListener("pressShowPerson", this);
        this.#peopleContainer.addEventListener("pressShowTree", this);
    }

    handleEvent(event) {
        switch(event.type) {
            case "resize":
                this.#toggleSizeMainContainer();
                break;
            case "DOMContentLoaded":
                this.#setStyle();
                break;
            case "click":
                let elem = event.currentTarget;
                switch (elem.id) {
                    case "people-dropdown-button":
                        this.#dropdownToggle();
                        break;
                    case "people-button-close":
                        this.#hideContainer();
                        break;
                }
                break;
            case "pressShowPerson":
                this.#hideContainerIf();
                break;
            case "pressShowTree":
                this.#hideContainerIf();
                break;
        }
    }

    #dropdownToggle()
    {
        this.#peopleContainer.classList.toggle("hidden");
        this.#toggleSizeMainContainer();
    }

    #hideContainer()
    {
        this.#peopleContainer.classList.add("hidden");
        this.#toggleSizeMainContainer();
    }

    #hideContainerIf() {
        if (document.documentElement.clientWidth < this.#mediaScreenMaxWidth) {
            this.#hideContainer();
        }
    }

    #toggleSizeMainContainer()
    {
        if (this.#peopleContainer.classList.contains("hidden")) {
            this.#layout.mainContainer.classList.add("main-container-full-width");
        } else {
            this.#layout.mainContainer.classList.remove("main-container-full-width");
        }
    }

    #setStyle()
    {
        if (document.documentElement.clientWidth < this.#mediaScreenMaxWidth) {
            this.#peopleContainer.classList.add("hidden");
        }
    }
}

export default new Container(
    layoutObject,
    collectionObject
);
