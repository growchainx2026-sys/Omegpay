@props(['file'])

<object data="{{ asset("/storage/".$file->file) }}#toolbar=0&navpanes=0&scrollbar=0"
        type="application/pdf"
        width="100%"
        height="100%">
    <p>Seu navegador não suporta visualizar PDFs. 
       <a href="{{ asset("/storage/".$file->file) }}">Baixar PDF</a></p>
</object>