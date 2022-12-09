"use strict";

import {showPerson, createPerson, editPerson, closePerson as goHome} from "./person/person.js";
import {showTreeIndex} from "./tree/tree.js";
import events from "./events/Events.js";
import layout from "./Layout.js";
import people from './people/Collection';
import sender from './Sender.js';
import spinner from "./Spinner.js";
import toast from './Toast.js';

window.addEventListener("popstate", updateDocument);

async function updateDocument() {
    spinner.on();
    try {
        if (!history.state) {
            location.reload();
            return;
        }

        let state = parseUrl(document.location.href);
        await people.update(
            state.people.search,
            state.people.order
            );

        if (state.main.person) {
            switch (state.main.person.crudMethod) {
                case "show": {
                    await showPerson(state.main.person.crudUrl);
                    break;
                }
                case "create": {
                    await createPerson(state.main.person.crudUrl);
                    break;
                }
                case "edit": {
                    await editPerson(state.main.person.crudUrl);
                    break;
                }
            }
        } else if (state.main.events !== null) {
            await events.show(state.main.events.url);
        } else if (state.main.tree !== null) {
            await showTreeIndex(
                state.main.tree.url,
                state.main.tree.person_id,
                state.main.tree.parent_id
                );
        } else {
            await goHome(layout.urlHomePart);
        }
    } catch (error) {
        toast.showErrorDefault();
        sender.sendLog(error);
    } finally {
        spinner.off();
    }
}

/**
 * @param {string} url
 * @returns {object}
 */
function parseUrl(url)
{
    let res = url.split("?");

    let orderDefault = 1;

    let state = {
        people: {
            search: "",
            order: orderDefault
        },
        main: {
            person: null,
            tree: null,
            events: null
        }
    };

    if (res[0]) {
        let partsUrl = res[0].split("/");
        if (partsUrl[3] === "person") {
            if (partsUrl[4] === "create") {
                state.main.person = {
                    crudMethod: "create",
                    crudUrl: history.state.url
                }
            } else if (partsUrl[4] && partsUrl[5] === "edit") {
                state.main.person = {
                    crudMethod: "edit",
                    crudUrl: history.state.url
                }
            } else if (partsUrl[4]) {
                state.main.person = {
                    crudMethod: "show",
                    crudUrl: history.state.url
                }
            } else {
                state.main.person = null;
            }
        } else if (partsUrl[3] === "events") {
            state.main.events = {
                url: history.state.url
            };
        } else if (partsUrl[3] === "tree") {
            state.main.tree = {
                url: history.state.url,
                person_id: partsUrl[4] ?? null,
                parent_id: partsUrl[5] ?? null
            };
        } else {
            
        }
    } else {
        throw new Error ("Invalid url. ");
    }

    if (res[1]) {
        let request = res[1];
        let requestItems = request.split("&");
        if (requestItems) {
            for (let item of requestItems) {
                let array = item.split("=");
                if (array[0] === "people_search") {
                    state.people.search = (array[1]) ? decodeURI(array[1]) : "";
                }
                if (array[0] === "people_order") {
                    state.people.order = (array[1]) ? array[1] : orderDefault;
                }
            }
        }
    }

    return state;
}
