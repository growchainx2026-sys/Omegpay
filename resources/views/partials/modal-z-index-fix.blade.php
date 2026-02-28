{{-- Fix global: modais Bootstrap SEMPRE acima do backdrop - modal vem NA FRENTE --}}
<style>
    /* Backdrop ABAIXO, modal ACIMA - ordem garantida */
    .modal-backdrop { z-index: 10050 !important; }
    .modal { z-index: 10100 !important; }
    .modal.show { z-index: 10100 !important; }
    .modal-dialog { z-index: 1 !important; position: relative; }
    .modal-content { z-index: 2 !important; position: relative; }
    .swal2-container { z-index: 10150 !important; }
</style>
<script>
(function() {
    'use strict';
    var BACKDROP_Z = 10050, MODAL_Z = 10100;

    function fixModalZIndex() {
        var backdrops = document.querySelectorAll('.modal-backdrop');
        var modals = document.querySelectorAll('.modal.show');
        backdrops.forEach(function(b) { b.style.zIndex = BACKDROP_Z.toString(); });
        modals.forEach(function(modal) {
            modal.style.zIndex = MODAL_Z.toString();
            var d = modal.querySelector('.modal-dialog');
            var c = modal.querySelector('.modal-content');
            if (d) d.style.zIndex = '1';
            if (c) c.style.zIndex = '2';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('show.bs.modal', function(ev) {
            var m = ev.target;
            if (m && m.classList.contains('modal')) document.body.appendChild(m);
        }, true);
        document.body.addEventListener('shown.bs.modal', fixModalZIndex, true);
        document.body.addEventListener('hidden.bs.modal', function() { setTimeout(fixModalZIndex, 80); }, true);
        var obs = new MutationObserver(function() {
            if (document.querySelector('.modal.show') || document.querySelector('.modal-backdrop')) fixModalZIndex();
        });
        obs.observe(document.body, { childList: true, subtree: true });
    });
})();
</script>
