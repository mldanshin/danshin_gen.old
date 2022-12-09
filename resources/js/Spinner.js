export default new class Spinner
{
    #elem;
    #lockScreen

    constructor()
    {
        this.#elem = document.getElementById("spinner");
        this.#lockScreen = document.getElementById("lock-screen");
    }

    /**
     * 
     * @param {boolean} isDisabledKeys 
     */
    on(isDisabledKeys = true) {
        this.#elem.classList.remove("hidden");
        this.#lockScreen.classList.remove("hidden");
        if (isDisabledKeys === true) {
            document.body.addEventListener("keydown", this.#handlerKeydown);
        }
    }
    
    off() {
        this.#elem.classList.add("hidden");
        this.#lockScreen.classList.add("hidden");
        document.body.removeEventListener("keydown", this.#handlerKeydown);
    }

    #handlerKeydown(event)
    {
        event.preventDefault();
    }
}
