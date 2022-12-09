export default class Messages
{
    #notValid;
    #error;
    #confirmation;
    #forbidden;
    #eventSubscriptionDeleteConfirmation;

    constructor()
    {
        this.#notValid = document.getElementById("message_not_valid").textContent;
        this.#error = document.getElementById("message_error").textContent;
        this.#confirmation = document.getElementById("message_confirmation").textContent;
        this.#forbidden = document.getElementById("message_forbidden").textContent;
        this.#eventSubscriptionDeleteConfirmation = document.getElementById("message_event_subscription_delete_confirmation").textContent;
    }

    get notValid()
    {
        return this.#notValid;
    }

    get confirmation()
    {
        return this.#confirmation;
    }

    get forbidden()
    {
        return this.#forbidden;
    }

    get error()
    {
        return this.#error;
    }

    get eventSubscriptionDeleteConfirmation()
    {
        return this.#eventSubscriptionDeleteConfirmation;
    }
}