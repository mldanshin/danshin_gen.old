"use strict";

import {treeInitializeButtonShow} from './tree.js';

let marker = null;
let coefficientIncrease = null;

function initialize()
{
    coefficientIncrease = getCoefficientIncrease();

    if (coefficientIncrease > 1) {
        marker = document.getElementById("marker-adding");

        let container = document.getElementById("tree-object-container");
        if (container) {
            container.addEventListener("click", (event) => {
                let target = event.target;
                if (!target.classList.contains("tree-person-container-increased")) {
                    removeAllIncreased();
                }
            });
            container.addEventListener("touchend", (event) => {
                let target = event.target;

                removeAllIncreased();

                if (target.classList.contains("tree-person-text")) {
                    event.preventDefault();
                    createIncreased(target.parentElement);
                }
            });
        }

        let personContainer = document.querySelectorAll(".tree-person-container");
        for (let item of personContainer) {
            item.addEventListener("mouseenter", (event) => {
                createIncreased(event.currentTarget);
            });
        }
    }
}

/** 
 * @param {object} element 
 */
function createIncreased(element)
{
    let clone = element.cloneNode(true);
    clone.classList.add("tree-person-container-increased");
    clone.setAttribute("transform", "scale(" + coefficientIncrease + ")");

    let childrens = clone.children;
    let rect = getRect(childrens);

    for (let child of childrens) {
        switch (child.nodeName) {
            case "image":
                let increasedOffsetX = child.x.baseVal.value - rect.point.increasedAutoX;
                let increasedOffsetY = child.y.baseVal.value - rect.point.increasedAutoY;
                child.x.baseVal.value = rect.point.actualX + increasedOffsetX;
                child.y.baseVal.value = rect.point.actualY + increasedOffsetY;
                break;
            case "text":
                let textIncreasedOffsetX = child.x.baseVal[0].value - rect.point.increasedAutoX;
                let textIncreasedOffsetY = child.y.baseVal[0].value - rect.point.increasedAutoY;
                child.setAttribute("x", rect.point.actualX + textIncreasedOffsetX);
                child.setAttribute("y", rect.point.actualY + textIncreasedOffsetY);
                child.classList.remove("tree-person-text");
                break;
            default:
                break;
        }
    }

    marker.insertAdjacentElement("afterend", clone);
    treeInitializeButtonShow();
    clone.addEventListener("mouseleave", () => {
        removeAllIncreased();
    });
}

/** 
 * @param {object} element 
 */
function removeIncreased(element)
{
    element.remove();
}

function removeAllIncreased()
{
    let personContainer = document.querySelectorAll(".tree-person-container-increased");
    for (let item of personContainer) {
        removeIncreased(item);
    }
}

/** 
 * @param {object} elements 
 * @returns {object?}
 */
function getRect(elements)
{
    for (let child of elements) {
        if (child.nodeName === "rect") {
            return {
                size: {
                    width: child.width.baseVal.value,
                    height: child.height.baseVal.value
                },
                point: setRectPoint(child)
            };
        }
    }

    return null;
}

/**
 * @param {object} elem 
 * @returns {object}
 */
function setRectPoint(elem)
{
    let increasedAutoX = elem.x.baseVal.value;
    let increasedAutoY = elem.y.baseVal.value;

    let originalX = increasedAutoX / coefficientIncrease;
    let originalY = increasedAutoY / coefficientIncrease;
    
    let increasedWidth = elem.width.baseVal.value;
    let originalWidth = elem.width.baseVal.value / coefficientIncrease;
    
    elem.x.baseVal.value = originalX - (increasedWidth / 2 - originalWidth / 2);
    if (elem.x.baseVal.value < 0) {
        elem.x.baseVal.value = 0;
    }

    let increasedHeight = elem.height.baseVal.value;
    let originalHeight = elem.height.baseVal.value / coefficientIncrease;
    
    elem.y.baseVal.value = originalY - (increasedHeight / 2 - originalHeight / 2);
    if (elem.y.baseVal.value < 0) {
        elem.y.baseVal.value = 0;
    }

    return {
        increasedAutoX: increasedAutoX,
        increasedAutoY: increasedAutoY,
        originalX: originalX,
        originalY: originalY,
        actualX: elem.x.baseVal.value,
        actualY: elem.y.baseVal.value,
    };
}

/**
 * @returns {number}
 */
function getCoefficientIncrease()
{
    let fontSize = getFontSize();
    if (fontSize === null) {
        return 1;
    }

    if (fontSize >= 12) {
        return 1;
    } else if (fontSize >= 8) {
        return 1.5;
    } else {
        return 2.2;
    }
}

/**
 * @returns {number?}
 */
function getFontSize()
{
    let styles = document.styleSheets;
    for (let style of styles) {
        if (style.title === "tree-style-svg") {
            let rules = style.cssRules;
            for (let rule of rules) {
                if (rule.selectorText === ".tree-font") {
                    let fontSize = rule.style["font-size"];
                    return Number(fontSize.replace("px", "").trim());
                }
            }
        }
    }

    return null;
}

export {initialize as initializeZoomerPerson}