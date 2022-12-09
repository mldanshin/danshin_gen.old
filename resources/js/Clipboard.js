export default new class Clipboard
{
    /**
     * 
     * @param {string} elementId
     */
    async copy(elementId)
    {
        let element = document.getElementById(elementId);
        if (!element) {
            throw new Error("The element with the passed id=" + elementId + " does not exist. ");
        }
    
        if (navigator.clipboard) {
            await navigator.clipboard.writeText(element.innerText).trim();
        } else {
            throw new Error("Navigator.clipboard = undefined. ");
        }
    }
}
