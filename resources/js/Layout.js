export default new class Layout
{
    #mainContainer;
    #csrf;
    #urlHome;
    #urlHomePart;
    #urlLog;

    constructor()
    {
        this.#mainContainer = document.getElementById("main-container");
        this.#csrf = document.getElementsByName("csrf-token")[0].content;
        this.#urlHome = document.getElementById("home-url").value;
        this.#urlHomePart = document.getElementById("home-url-part").value;
        this.#urlLog = document.getElementById("log-url").value;
    }

    get mainContainer()
    {
        return this.#mainContainer;
    }

    get urlHome()
    {
        return this.#urlHome;
    }

    get urlHomePart()
    {
        return this.#urlHomePart;
    }

    get urlLog()
    {
        return this.#urlLog;
    }

    get csrf()
    {
        return this.#csrf;
    }
}
