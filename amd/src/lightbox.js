/**
 * Lightbox for PPT Book.
 * @module mod_pptbook/lightbox
 */
define([], function() {
    "use strict";

    /**
     * CSS selectors used by the lightbox.
     * @type {{thumbs: string, modal: string, image: string}}
     */
    const SELECTORS = {thumbs: ".pptbook-zoom", modal: "#pptbook-modal", image: "#pptbook-modal-img"};

    /**
     * Open the modal with a given image URL.
     * @param {HTMLElement} modal The modal container element.
     * @param {HTMLImageElement} img The image element inside the modal.
     * @param {string} url The full image URL to display.
     * @return {void}
     */
    const open = function(modal, img, url) {
        if (!url) {
            return;
        }
        img.setAttribute("src", url);
        modal.setAttribute("aria-hidden", "false");
        modal.classList.add("is-open");
    };

    /**
     * Close the modal and reset image source.
     * @param {HTMLElement} modal The modal container element.
     * @param {HTMLImageElement} img The image element inside the modal.
     * @return {void}
     */
    const close = function(modal, img) {
        modal.classList.remove("is-open");
        modal.setAttribute("aria-hidden", "true");
        img.setAttribute("src", "about:blank");
    };

    /**
     * Initialize lightbox bindings.
     * Binds click handlers to thumbnails and ESC/overlay close.
     * @return {void}
     */
    const init = function() {
        const modal = document.querySelector(SELECTORS.modal);
        const img = document.querySelector(SELECTORS.image);
        if (!modal || !img) {
            return;
        }

        document.querySelectorAll(SELECTORS.thumbs).forEach(function(a) {
            a.addEventListener("click", function(e) {
                e.preventDefault();
                const url = a.getAttribute("data-full") || a.getAttribute("href");
                open(modal, img, url);
            });
        });

        modal.addEventListener("click", function(e) {
            // Close if the user clicks outside the image (overlay) or the image itself.
            if (e.target === modal || e.target === img) {
                close(modal, img);
            }
        });

        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                close(modal, img);
            }
        });
    };

    return {init: init};
});
