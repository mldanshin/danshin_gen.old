"use strict";

import events from '../Events.js';
import ListInputComponent from "./partials/ListInputComponent.js";
import layout from '../Layout.js';
import Messages from "../Messages.js";
import Marriages from "./partials/Marriages.js";
import Parents from "./partials/Parents.js";
import peopleFilterOrdering from '../people/FilterOrdering.js';
import Photo from "./partials/Photo.js";
import sender from '../Sender.js';
import spinner from "../Spinner.js";
import toast from '../Toast';

document.body.addEventListener("pressShowPerson", (event) => {showByButton(event.target)});
document.body.addEventListener("exitSubscription", () => {
    closeBy(layout.urlHome, layout.urlHomePart)
});

const messages = new Messages();

initializeFormEdit();
addEventListenerForButtons();
addEventListenerForButtonFamily();
createPartials();

function addEventListenerForButtons()
{
    let showButton = document.getElementById("person-show-button");
    if (showButton) {
        showButton.addEventListener("click", (event) => {showByButton(event.currentTarget)});
    }

    let createButton = document.getElementById("person-create-button");
    if (createButton) {
        createButton.addEventListener("click", (event) => {createByButton(event.currentTarget)});
    }
    
    let editButton = document.getElementById("person-edit-button");
    if (editButton) {
        editButton.addEventListener("click", (event) => {editByButton(event.currentTarget)});
    }
    
    let destroyButton = document.getElementById("person-destroy-button");
    if (destroyButton) {
        destroyButton.addEventListener("click", destroy);
    }

    let treeButton = document.getElementById("person-tree-button");
    if (treeButton) {
        treeButton.addEventListener("click", showTreeIndex);
    }

    let closeButton = document.getElementById("person-close-button");
    if (closeButton) {
        closeButton.addEventListener("click", (event) => {
            closeBy(event.currentTarget.dataset.href, event.currentTarget.dataset.hrefPart)
        });
    }
}

function addEventListenerForButtonFamily()
{
    let showRelativePersonButtons = document.querySelectorAll(".person__button-show-person");
    for (let item of showRelativePersonButtons) {
        item.addEventListener("click", (event) => {showByButton(event.currentTarget)});
    }

    let showRelativeTreeButtons = document.querySelectorAll(".person__button-show-tree");
    for (let item of showRelativeTreeButtons) {
        item.addEventListener("click", showTreeIndex);
    }
}

function createPartials()
{
    new ListInputComponent(sender, spinner, toast);
    new Marriages(sender, spinner, toast);
    new Parents(sender, spinner, toast);
    new Photo(sender, spinner, toast);
}

function initializeFormEdit()
{
    let formEdit = document.getElementById("person-edit-form");
    if (formEdit) {
        formEdit.addEventListener("submit", save);
    }
}

/**
 * @param {object} button 
 */
async function showByButton(button)
{
    spinner.on();
    try {
        let url = button.dataset.href;
        let urlPart = button.dataset.hrefPart;
        await show(urlPart);
        history.pushState({url: urlPart}, null, url + peopleFilterOrdering.getRequest());
    } catch (error) {
        toast.showErrorDefault();
        sender.sendLog(error);
    } finally {
        spinner.off();
    }
}

/**
 * @param {string} url 
 */
async function show(url)
{
    let response = await sender.sendRequest(url);
    if (response && response.status === 200) {
        layout.mainContainer.innerHTML = await response.text();
        addEventListenerForButtonFamily();
        addEventListenerForButtons();
    }
}

async function createByButton(button)
{
    spinner.on();
    try {
        let url = button.dataset.href;
        let urlPart = button.dataset.hrefPart;
        await create(urlPart);
        history.pushState({url: urlPart}, null, url + peopleFilterOrdering.getRequest());
    } catch (error) {
        toast.showErrorDefault();
        sender.sendLog(error);
    } finally {
        spinner.off();
    }
}

/**
 * @param {string} url 
 */
async function create(url)
{
    let response = await sender.sendRequest(url);
    if (response && response.status === 200) {
        layout.mainContainer.innerHTML = await response.text();
        addEventListenerForButtons();
        initializeFormEdit();
        createPartials();
    }
}

async function editByButton(button)
{
    spinner.on();
    try {
        let url = button.dataset.href;
        let urlPart = button.dataset.hrefPart;
        await edit(urlPart);
        history.pushState({url: urlPart}, null, url + peopleFilterOrdering.getRequest());
    } catch (error) {
        toast.showErrorDefault();
        sender.sendLog(error);
    } finally {
        spinner.off();
    }
}

/**
 * @param {string} url 
 */
async function edit(url)
{
    let response = await sender.sendRequest(url);
    if (response && response.status === 200) {
        layout.mainContainer.innerHTML = await response.text();
        addEventListenerForButtons();
        initializeFormEdit();
        createPartials();
    }
}

async function save(event)
{
    spinner.on();

    event.preventDefault();

    try {
        let form = event.currentTarget;
        let formData = new FormData(form);

        let response = await sender.sendRequest(form.action, {method: 'POST', body: formData});
        if (response.redirected) {
            toast.show(messages.notValid);

            layout.mainContainer.innerHTML = await response.text();
            addEventListenerForButtons();
            initializeFormEdit();
            createPartials();
        } else if (response.status === 200) {
            let json = await response.json();
            layout.mainContainer.innerHTML = json.body;

            toast.show(json.message);

            addEventListenerForButtonFamily();
            addEventListenerForButtons();

            let person = document.getElementById("person");
            let urlPart = person.dataset.hrefPart;
            history.pushState({url: urlPart}, null, person.dataset.href + peopleFilterOrdering.getRequest());

            layout.mainContainer.dispatchEvent(events.personChange);
        }
    } catch (error) {
        toast.showErrorDefault();
        sender.sendLog(error);
    } finally {
        spinner.off();
    }
}

async function destroy(event)
{
    event.preventDefault();
    let elem = event.currentTarget;

    if (confirmAction()) {
        spinner.on();
        try {
            let form = document.getElementById("person-destroy-form");
            let formData = new FormData(form);
        
            let response = await sender.sendRequest(form.action, {method: 'POST', body: formData});
            if (response && response.status === 200) {
                let json = await response.json();

                layout.mainContainer.innerHTML = json.body;

                toast.show(json.message);

                layout.mainContainer.dispatchEvent(events.personChange);
            }

            addEventListenerForButtons();

            history.replaceState(null, null, elem.dataset.href + peopleFilterOrdering.getRequest());
        } catch (error) {
            toast.showErrorDefault();
            sender.sendLog(error);
        } finally {
            spinner.off();
        }
    }
}

/**
 * 
 * @param {string} url
 * @param {string} urlPart
 */
async function closeBy(url, urlPart)
{
    spinner.on();

    try {
        await close(urlPart);
        history.pushState({url: urlPart}, null, url + peopleFilterOrdering.getRequest());
    } catch (error) {
        toast.showErrorDefault();
        sender.sendLog(error);
    } finally {
        spinner.off();
    }
}

/**
 * @param {string} urlPart 
 */
async function close(urlPart)
{
    let response = await sender.sendRequest(urlPart);
    if (response && response.status === 200) {
        layout.mainContainer.innerHTML = await response.text();
        addEventListenerForButtons();
    }
}

/**
 * 
 * @returns {boolean}
 */
function confirmAction()
{
    return confirm(messages.confirmation);
}

function showTreeIndex(event)
{
    event.currentTarget.dispatchEvent(events.pressShowTree);
}

export {
    show as showPerson,
    create as createPerson,
    edit as editPerson,
    close as closePerson
};
