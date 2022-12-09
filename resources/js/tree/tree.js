"use strict";

import {initializeZoomer} from './zoomer.js';
import {initializeZoomerPerson} from './zoomerPerson.js';
import events from '../Events.js';
import layout from '../Layout.js';
import peopleFilterOrdering from '../people/FilterOrdering.js';
import sender from '../Sender.js';
import spinner from "../Spinner.js";
import toast from '../Toast.js';

window.addEventListener("load", showIndexByLoading);
document.body.addEventListener("pressShowTree", (event) => {showIndexByButton(event.target)});

addEventListener();

function addEventListener()
{
    let toggle = document.getElementById("tree-toggle");
    if (toggle) {
        toggle.addEventListener("change", showByToggle);
    }

    let buttonHelp = document.getElementById("tree-control-help-button");
    if (buttonHelp) {
        buttonHelp.addEventListener("click", toggleHelp);
    }

    initializeButtonShow();
    initializeZoomer();
    initializeZoomerPerson();
}

function initializeButtonShow()
{
    let buttonsShowTree = document.querySelectorAll(".tree__button-show-tree");
    for (let item of buttonsShowTree) {
        item.addEventListener("click", (event) => {showIndexByButton(event.currentTarget)});
        item.addEventListener("touchend", (event) => {showIndexByButton(event.currentTarget)});
    }

    let buttonsShowPerson = document.querySelectorAll(".tree__button-show-person");
    for (let item of buttonsShowPerson) {
        item.addEventListener("click", showPerson);
        item.addEventListener("touchend", showPerson);
    }
}

/**
 * @param {object} event 
 */
async function showByToggle(event)
{
    spinner.on();
    try {
        let elem = event.currentTarget;
        let option = elem.options[elem.selectedIndex];

        let person = option.dataset.person;
        let parent = option.dataset.parent;
        let url = option.dataset.href;
        let urlPartIndex = option.dataset.hrefPartIndex;
        let urlPartShow = option.dataset.hrefPartShow;
        let urlDownload = option.dataset.hrefDownload;
        let urlShowImage = option.dataset.hrefShowImage;

        let response = await sender.sendRequest(urlPartShow, {method: 'POST', body: getFormData(person, parent)});
        if (response && response.status === 200) {
            let container = document.getElementById("tree-object-container");
            container.innerHTML = await response.text();
            addEventListener();
            changeLinkDownload(urlDownload);
            changeLinkShowImage(urlShowImage);
            scrollTargetPerson();
            history.pushState({url: urlPartIndex}, null, url + peopleFilterOrdering.getRequest());
        }
    } catch (error) {
        toast.showErrorDefault();
        sender.sendLog(error);
    } finally {
        spinner.off();
    }
}

/**
 * @param {object} button 
 */
async function showIndexByButton(button)
{
    spinner.on();
    try {
        let person = button.dataset.person;
        let url = button.dataset.href;
        let urlPart = button.dataset.hrefPart;

        await showIndex(urlPart, person);
        history.pushState({url: urlPart}, null, url + peopleFilterOrdering.getRequest());
    } catch (error) {
        toast.showErrorDefault();
        sender.sendLog(error);
    } finally {
        spinner.off();
    }
}

async function showIndexByLoading()
{
    let form = document.getElementById("tree-form");
    if (form) {
        spinner.on();
        try {
            await showIndex(
                form.action,
                form.person_id.value,
                form.parent_id?.value ?? null
                );
        } catch (error) {
            toast.showErrorDefault();
            sender.sendLog(error);
        } finally {
            spinner.off();
        }
    }
}

/**
 * @param {string} urlPart
 * @param {number} person 
 * @param {number?} parent 
 */
async function showIndex(urlPart, person, parent = null)
{
    let response = await sender.sendRequest(urlPart, {method: 'POST', body: getFormData(person, parent)});
    if (response && response.status === 200) {
        layout.mainContainer.innerHTML = await response.text();
        addEventListener();
        scrollTargetPerson();
    }
}

function showPerson(event)
{
    event.currentTarget.dispatchEvent(events.pressShowPerson);
}

/**
 * 
 * @param {string} url
 */
function changeLinkDownload(url)
{
    let link = document.getElementById("tree-download-button");
    link.setAttribute("href", url);
}

/**
 * 
 * @param {string} url
 */
 function changeLinkShowImage(url)
 {
     let link = document.getElementById("tree-show-image-button");
     link.setAttribute("href", url);
 }

function scrollTargetPerson()
{
    let container = document.getElementById("tree-object-container");
    let targetPerson = document.getElementById("tree-person-basic");

    let x = targetPerson.x.baseVal.value + (targetPerson.width.baseVal.value / 2) - (container.offsetWidth / 2);
    let y = (container.offsetTop + targetPerson.y.baseVal.value) + (targetPerson.height.baseVal.value / 2) - (document.body.offsetHeight / 2);

    window.scrollTo(0, y);
    container.scrollTo(x, 0);
}

/**
 * 
 * @param {number} person 
 * @param {number?} parent 
 * @returns {FormData}
 */
function getFormData(person, parent = null)
{
    let formData = new FormData();
    formData.append("person_id", person);
    if (parent) {
        formData.append("parent_id", parent);
    }
    formData.append("width_screen", document.documentElement.clientWidth);
    formData.append("height_screen", document.documentElement.clientHeight);
    formData.append("height_screen", document.documentElement.clientHeight);

    return formData;
}

function toggleHelp()
{
    let help = document.getElementById("tree-control-help");
    help.classList.toggle("hidden");
}

export {
    showIndex as showTreeIndex,
    initializeButtonShow as treeInitializeButtonShow
};
