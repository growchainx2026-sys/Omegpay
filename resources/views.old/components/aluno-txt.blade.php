@props(['file'])

<object data="{{ asset('/storage/'.$file->file) }}"
        type="text/plain"
        width="100%"
        height="100%">
    <p>Seu navegador não suporta visualizar arquivos TXT. 
       <a href="{{ asset('/storage/'.$file->file) }}">Baixar TXT</a></p>
</object>