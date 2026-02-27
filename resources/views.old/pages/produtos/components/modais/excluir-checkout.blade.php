@props(['checkout'])
<div class="modal fade" id="excluir{{ $checkout->id }}" tabindex="-1" aria-labelledby="excluir{{ $checkout->id }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="excluir{{ $checkout->id }}Label">Excluir checkout</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja excluir o checkout <strong>{{ $checkout->name }}</strong> ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary"
                    onclick="excluirCheckout('{{$checkout->id}}')">Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
    function excluirCheckout(id)
    {
        fetch(`/checkout/excluir/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({})
        })
        .then(res=>res.json())
        .then((res)=>{
            if(res.status){
                showToast('success', 'Checkout excluído com sucesso.');
                setTimeout(()=>{
                    window.location.reload();
                },3000)
            }
        })
        .catch((err)=>{
            showToast('error', 'Erro ao excluir checkout.')
        })
    }
</script>