import type { ListaComponentProps } from "../types/components";


export const listaData: ListaComponentProps = {
    id: Date.now().toString(),
    type: 'lista',
    items: ['Item 1', 'Item 2', 'Item 3'],
    icone: 'check',
    corIcone: '#059669',
    temTitulo: false,
    titulo: 'Título da Lista',
    corFundo: '#ffffff',
    corTexto: '#000000',
    alinhamento: 'left',
    tamanho: 16,
    background: 'transparent'
}