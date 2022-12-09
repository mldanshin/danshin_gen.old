"use strict";

import Messages from "./Messages.js";

class Toast
{
    #container;
    #messages;

    /**
     * @param {object} messages 
     */
    constructor(messages)
    {
        this.#messages = messages;
        this.#container = document.getElementById("toast-container");
    }

    /**
     * @param {string} message 
     */
    show(message)
    {
        this.#run(message);
    }

    showErrorDefault()
    {
        this.#run(this.#messages.error);
    }

    /**
     * @param {string} message 
     */
    #run(message)
    {
        let elem = document.getElementById("toast");
        if (elem) {
            elem.remove();
        }

        let elemNew = document.createElement("div");
        elemNew.id = "toast";
        elemNew.innerHTML = message;
        this.#container.append(elemNew);
    }
}

export default new Toast(new Messages());
