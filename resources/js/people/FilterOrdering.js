class FilterOrdering
{
    #form;
    #search;
    #orderGroup;
    #eventChange;
    
    constructor()
    {
        this.#form = document.getElementById("people-form");
        this.#search = document.getElementById("people-search");
        this.#orderGroup = document.getElementsByName("people_order");

        this.#eventChange = new CustomEvent("changeFilterOrdering", {bubbles: true});

        this.#form.addEventListener("submit", this);
        this.#search.addEventListener("keyup", this);
        this.#createOrderGroup();
    }

    get url()
    {
        return this.#form.action;
    }

    /**
     * @returns {string}
     */
    getRequest()
    {
        return "?people_search=" + this.getValueSearch() + "&people_order=" + this.getValueOrderGroup();
    }

    /**
     * @returns {number}
     */
    getValueOrderGroup()
    {
        for (let item of this.#orderGroup) {
            let checked = item.checked;
            if (checked === true) {
                return item.value;
            }
        }

        return 1;
    }
 
    /**
     * @param {number} value 
     */
    setValueOrderGroup(value)
    {
        for (let item of this.#orderGroup) {
            if (item.value == value) {
                item.checked = true;
            }
        }
    }

    /**
     * @returns {string} 
     */
    getValueSearch()
    {
        return this.#search.value;
    }

    /**
     * @param {string} value 
     */
    setValueSearch(value)
    {
        this.#search.value = value;
    }

    handleEvent(event) {
        switch(event.type) {
            case "change":
                this.#form.dispatchEvent(this.#eventChange);
                break;
            case "keyup":
                this.#form.dispatchEvent(this.#eventChange);
                break;
            case "submit":
                this.#submitForm(event);
                break;
        }
    }

    #submitForm(event)
    {
        event.preventDefault();
        this.#search.blur();
        this.#form.dispatchEvent(this.#eventChange);
    }

    #createOrderGroup()
    {
        for (let item of this.#orderGroup) {
            item.addEventListener("change", this);
        }
    }
}

export default new FilterOrdering();
