import type { HeaderComponentProps } from "../types/components";


export const headerData: HeaderComponentProps = {
    id: Date.now().toString(),
    type: 'header',
    color: "#000000",
    title: 'Nome do seu produto',
    fontSize: '48',
    align: 'left',
    padding: 0,
    image: '/product-placeholder.svg',
    background: 'transparent'
}