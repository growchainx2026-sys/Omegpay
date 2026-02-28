{{-- Fix global: modais Bootstrap sempre acima do backdrop (tela preta) - aplica a TODOS os modais --}}
<style>
    /* Z-index: modal SEMPRE acima do backdrop - valores altos para sobrepor sidebar/drawer */
    .modal-backdrop { z-index: 10050 !important; }
    .modal { z-index: 10060 !important; }
    .modal-dialog { z-index: 10061 !important; position: relative; }
    .modal-content { z-index: 10062 !important; position: relative; }
    /* SweetAlert2 */
    .swal2-container { z-index: 10065 !important; }
</style>
<script>
(function() {
    'use strict';
    var BASE_BACKDROP = 10050, BASE_MODAL = 10060;

    function fixModalZIndex() {
        var backdrops = document.querySelectorAll('.modal-backdrop');
        var modals = document.querySelectorAll('.modal.show');
        backdrops.forEach(function(b, i) { b.style.zIndex = (BASE_BACKDROP + i * 10).toString(); });
        modals.forEach(function(modal, i) {
            modal.style.zIndex = (BASE_MODAL + i * 10).toString();
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
