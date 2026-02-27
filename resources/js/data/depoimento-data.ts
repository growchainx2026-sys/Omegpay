import type { DepoimentoComponentProps } from "../types/components";


export const depoimentoData: DepoimentoComponentProps = {
    id: Date.now().toString(),
    type: 'depoimento',
    photo: '/default-avatar.png',
    depoimento: 'Digite seu depoimento aqui',
    estrelas: 5,
    nome: 'John Doe',
    corFundo: '#FFFFFF',
    corTexto: '#000000',
    modoHorizontal: false
}