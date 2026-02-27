import type { ContadorComponentProps } from "../types/components";


export const contadorData: ContadorComponentProps = {
    id: Date.now().toString(),
    type: 'contador',
    tipo: 'data',
    time: '00:30',
    textActive: 'Oferta por tempo limitado',
    textFinalizado: 'Oferta finalizada',
    textColor: '#FFFFFF',
    bgColor: '#F50000'
}