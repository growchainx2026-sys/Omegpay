export type TextComponentProps = {
  id: string
  type: 'text'
  color: string
  text: string
  fontSize: number
  borderColor: string
  backgroundColor: string
  borderWidth: number
  borderRadius: number
  background: any
}

export type ImageComponentProps = {
  id: string
  type: 'image'
  url: string
  align: 'left' | 'center' | 'right'
  size: number
  redirectUrl: string
  background: any
}

export type VantagemComponentProps = {
  id: string
  type: 'vantagem'
  icon: any
  title: string
  subtitle: string
  title_color: string
  subtitle_color: string
  mode: 'vertical' | 'horizontal'
  background: any
}

export type SeloComponentProps = {
  id: string
  type: 'selo'
  selo: '1' | '2' | '3'
  header: string
  title: string
  subtitle: string
  title_color: string
  color: string
  align: 'left' | 'center' | 'right'
  background: any
}

export type HeaderComponentProps = {
  id: string
  type: 'header'
  title: string
  color: string
  fontSize: string | number
  image: string
  align: 'left' | 'right'
  padding: string | number
  background: any
}

export type ListaComponentProps = {
  id: string
  type: 'lista'
  items: string[]
  icone: 'nenhum' | 'check' | 'decimal' | 'circulo'
  corIcone: string
  temTitulo: boolean
  titulo?: string
  corFundo: string
  corTexto: string
  align: 'left' | 'center' | 'right'
  tamanho: number
  background: any
}

export type ContadorComponentProps = {
  id: string
  type: 'contador'
  tipo: 'data' | 'time'
  time: any
  textActive: string
  textFinalizado: string
  textColor: string
  bgColor: string
}

export type DepoimentoComponentProps = {
  id: string
  type: 'depoimento'
  photo: string
  depoimento: string
  estrelas: number
  nome: string
  corFundo: string
  corTexto: string
  modoHorizontal: boolean
}

export type Component = TextComponentProps | ImageComponentProps | VantagemComponentProps | SeloComponentProps | HeaderComponentProps | ListaComponentProps | ContadorComponentProps | DepoimentoComponentProps
