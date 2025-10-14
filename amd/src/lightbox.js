define(['jquery'], function($) {
    'use strict';

    const SEL = {
        link: '.pptbook-zoom',
        modal: '#pptbook-modal',
        img:   '#pptbook-modal-img'
    };

    const open = (src, alt) => {
        const $m = $(SEL.modal);
        const $img = $(SEL.img);
        if (!src) {
            return;
        }
        $img.attr('src', src);
        if (alt) {
            $img.attr('alt', alt);
        }
        $m.css('display', 'flex').attr('aria-hidden', 'false');
        $('body').addClass('pptbook-modal-open');
    };

    const close = () => {
        const $m = $(SEL.modal);
        const $img = $(SEL.img);
        $m.css('display', 'none').attr('aria-hidden', 'true');
        $img.removeAttr('src'); // empty-src avoiding.
        $('body').removeClass('pptbook-modal-open');
    };

    const bind = () => {
        // Thumbnails klick -> open.
        $(document).on('click', SEL.link, function(e) {
            e.preventDefault();
            const $a = $(this);
            const src = $a.data('full') || $a.attr('href');
            const alt = $a.find('img').attr('alt') || '';
            open(src, alt);
        });

        // clic to image -> close.
        $(document).on('click', SEL.modal, function(e) {
            if (e.target.id === 'pptbook-modal' || e.target.id === 'pptbook-modal-img') {
                close();
            }
        });

        // ESC close.
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape') {
                close();
            }
        });
    };

    return { init: bind };
});
