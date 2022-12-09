export default new class Events
{
    #pressShowPerson;
    #pressShowTree;
    #personChange;

    constructor()
    {
        this.#pressShowPerson = new CustomEvent("pressShowPerson", {bubbles: true});
        this.#pressShowTree = new CustomEvent("pressShowTree", {bubbles: true});
        this.#personChange = new CustomEvent("personChange", {bubbles: true});
    }

    get pressShowPerson()
    {
        return this.#pressShowPerson;
    }

    get pressShowTree()
    {
        return this.#pressShowTree;
    }

    get personChange()
    {
        return this.#personChange;
    }
}
