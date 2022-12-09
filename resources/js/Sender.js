"use strict";

import Messages from "./Messages.js";
import LayoutObject from "./Layout.js";
import toastObject from './Toast';

class Sender
{
    #layout;
    #messages;
    #toast;

    /**
     * @param {object} layout 
     * @param {object} messages 
     * @param {object} toast 
     */
    constructor(layout, messages, toast)
    {
        this.#layout = layout;
        this.#messages = messages;
        this.#toast = toast;
    }

    /**
     * @param {RequestInfo} url
     * @param {RequestInit} data
     * @return {Promise<Response>} response
     */
    async sendRequest(url, data = null)
    {
        if (data !== null && data.body) {
            data.body.append("_token", this.#layout.csrf);
        }

        let response = await fetch(url, data);

        if (response.headers.get("X-Authenticate")) {
            document.location.reload();
            return;
        }

        if (response.status === 403) {
            this.#toast.show(this.#messages.forbidden);
            return;
        }

        if (!response.ok) {
            throw new Error("Response error. ");
        }

        return response;
    }
 
    /**
     * 
     * @param {object} error 
     */
    async sendLog(error)
    {
        let message = error.name + ". " + error.message + ". \r\n" + "Stack: " + error.stack;

        let formData = new FormData();
        formData.append("message", message);

        this.sendRequest(
            this.#layout.urlLog,
            {method: "POST", body: formData}
            );
    }
}

export default new Sender(LayoutObject, new Messages(), toastObject);
