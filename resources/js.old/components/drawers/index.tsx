import { TextDrawer } from './TextDrawer'
import { ImageDrawer } from './ImageDrawer'
import { VantagemDrawer } from './VantagemDrawer'
import { SeloDrawer } from './SeloDrawer'
import { HeaderDrawer } from './HeaderDrawer'
import { ListaDrawer } from './ListDrawer'
import { ContadorDrawer } from './CronometroDrawer'
import { DepoimentoDrawer } from './DepoimentoDrawer'
import type {
    Component, ContadorComponentProps, DepoimentoComponentProps,
    HeaderComponentProps, ImageComponentProps, ListaComponentProps, SeloComponentProps,
    TextComponentProps, VantagemComponentProps
} from '../../types/components'

export interface DrawersProps {
    selectedComponent: Component | any,
    isDrawerOpen: boolean,
    setIsDrawerOpen: (isDrawerOpen: boolean) => void,
    updateComponent: (id: string, data: any) => void,
    handleDelete: () => void,
    handleDuplicate: () => void,
    background: string,
    setBackground: (background: string) => void
}

export const Drawers = ({
    selectedComponent, isDrawerOpen,
    setIsDrawerOpen, updateComponent,
    handleDelete, handleDuplicate,
    background, setBackground
}: DrawersProps) => {

    return (
        <>
            {selectedComponent?.type === 'text' && (
                <TextDrawer
                    isOpen={isDrawerOpen}
                    onClose={() => setIsDrawerOpen(false)}
                    text={(selectedComponent as TextComponentProps).text}
                    setText={(text) => updateComponent(selectedComponent.id, { text })}
                    color={(selectedComponent as TextComponentProps).text}
                    setColor={(color) => updateComponent(selectedComponent.id, { color })}
                    fontSize={(selectedComponent as TextComponentProps).fontSize}
                    setFontSize={(fontSize) => updateComponent(selectedComponent.id, { fontSize })}
                    borderColor={(selectedComponent as TextComponentProps).borderColor}
                    setBorderColor={(borderColor) => updateComponent(selectedComponent.id, { borderColor })}
                    backgroundColor={(selectedComponent as TextComponentProps).backgroundColor}
                    setBackgroundColor={(backgroundColor) => updateComponent(selectedComponent.id, { backgroundColor })}
                    borderWidth={(selectedComponent as TextComponentProps).borderWidth}
                    setBorderWidth={(borderWidth) => updateComponent(selectedComponent.id, { borderWidth })}
                    borderRadius={(selectedComponent as TextComponentProps).borderRadius}
                    setBorderRadius={(borderRadius) => updateComponent(selectedComponent.id, { borderRadius })}
                    onDelete={handleDelete}
                    onDuplicate={handleDuplicate}
                />
            )}
            {selectedComponent?.type === 'image' && (
                <ImageDrawer
                    isOpen={isDrawerOpen}
                    onClose={() => setIsDrawerOpen(false)}
                    url={(selectedComponent as ImageComponentProps).url}
                    setUrl={(url) => updateComponent(selectedComponent.id, { url })}
                    align={(selectedComponent as ImageComponentProps).align}
                    setalign={(align) => updateComponent(selectedComponent.id, { align })}
                    size={(selectedComponent as ImageComponentProps).size}
                    setSize={(size) => updateComponent(selectedComponent.id, { size })}
                    redirectUrl={(selectedComponent as ImageComponentProps).redirectUrl}
                    setRedirectUrl={(redirectUrl) => updateComponent(selectedComponent.id, { redirectUrl })}
                    onDelete={handleDelete}
                    onDuplicate={handleDuplicate}
                />

            )}
            {selectedComponent?.type === 'vantagem' && (
                <VantagemDrawer
                    isOpen={isDrawerOpen}
                    onClose={() => setIsDrawerOpen(false)}
                    icon={(selectedComponent as VantagemComponentProps).icon}
                    setIcon={(icon) => updateComponent(selectedComponent.id, { icon })}
                    title={(selectedComponent as VantagemComponentProps).title}
                    setTitle={(title) => updateComponent(selectedComponent.id, { title })}
                    subtitle={(selectedComponent as VantagemComponentProps).subtitle}
                    setSubtitle={(subtitle) => updateComponent(selectedComponent.id, { subtitle })}
                    title_color={(selectedComponent as VantagemComponentProps).title_color}
                    setTitleColor={(title_color) => updateComponent(selectedComponent.id, { title_color })}
                    subtitle_color={(selectedComponent as VantagemComponentProps).subtitle_color}
                    setSubtitleColor={(subtitle_color) => updateComponent(selectedComponent.id, { subtitle_color })}
                    mode={(selectedComponent as VantagemComponentProps).mode}
                    setMode={(mode) => updateComponent(selectedComponent.id, { mode })}
                    onDelete={handleDelete}
                    onDuplicate={handleDuplicate}
                />
            )}

            {selectedComponent?.type === 'selo' && (
                <SeloDrawer
                    isOpen={isDrawerOpen}
                    onClose={() => setIsDrawerOpen(false)}
                    selo={(selectedComponent as SeloComponentProps).selo}
                    setSelo={(selo) => updateComponent(selectedComponent.id, { selo })}
                    header={(selectedComponent as SeloComponentProps).header}
                    setHeader={(header) => updateComponent(selectedComponent.id, { header })}
                    title={(selectedComponent as SeloComponentProps).title}
                    setTitle={(title) => updateComponent(selectedComponent.id, { title })}
                    subtitle={(selectedComponent as SeloComponentProps).subtitle}
                    setSubtitle={(subtitle) => updateComponent(selectedComponent.id, { subtitle })}
                    title_color={(selectedComponent as SeloComponentProps).title_color}
                    setTitleColor={(title_color) => updateComponent(selectedComponent.id, { title_color })}
                    color={(selectedComponent as SeloComponentProps).color}
                    setColor={(color) => updateComponent(selectedComponent.id, { color })}
                    align={(selectedComponent as SeloComponentProps).align}
                    setAlign={(align) => updateComponent(selectedComponent.id, { align })}
                    onDelete={handleDelete}
                    onDuplicate={handleDuplicate}
                />
            )}

            {selectedComponent?.type === 'header' && (
                <HeaderDrawer
                    isOpen={isDrawerOpen}
                    onClose={() => setIsDrawerOpen(false)}
                    title={(selectedComponent as HeaderComponentProps).title}
                    setTitle={(title) => updateComponent(selectedComponent.id, { title })}
                    color={(selectedComponent as HeaderComponentProps).color}
                    setColor={(color) => updateComponent(selectedComponent.id, { color })}
                    align={(selectedComponent as HeaderComponentProps).align}
                    setalign={(align) => updateComponent(selectedComponent.id, { align })}
                    fontSize={(selectedComponent as HeaderComponentProps).fontSize}
                    setFontSize={(fontSize) => updateComponent(selectedComponent.id, { fontSize })}
                    image={(selectedComponent as HeaderComponentProps).image}
                    setImage={(image) => updateComponent(selectedComponent.id, { image })}
                    padding={(selectedComponent as HeaderComponentProps).padding}
                    setPadding={(padding) => updateComponent(selectedComponent.id, { padding })}
                    onDelete={handleDelete}
                    onDuplicate={handleDuplicate}
                />

            )}

            {selectedComponent?.type === 'lista' && (
                <ListaDrawer
                    isOpen={isDrawerOpen}
                    onClose={() => setIsDrawerOpen(false)}
                    items={(selectedComponent as ListaComponentProps).items}
                    setItems={(items) => updateComponent(selectedComponent.id, { items })}
                    icone={(selectedComponent as ListaComponentProps).icone}
                    setIcone={(icone) => updateComponent(selectedComponent.id, { icone })}
                    corIcone={(selectedComponent as ListaComponentProps).corIcone}
                    setCorIcone={(corIcone) => updateComponent(selectedComponent.id, { corIcone })}
                    temTitulo={(selectedComponent as ListaComponentProps).temTitulo}
                    setTemTitulo={(temTitulo) => updateComponent(selectedComponent.id, { temTitulo })}
                    titulo={(selectedComponent as ListaComponentProps).titulo}
                    setTitulo={(titulo) => updateComponent(selectedComponent.id, { titulo })}
                    corFundo={background}
                    setCorFundo={(corFundo) => setBackground(corFundo)}
                    corTexto={(selectedComponent as ListaComponentProps).corTexto}
                    setCorTexto={(corTexto) => updateComponent(selectedComponent.id, { corTexto })}
                    alinhamento={(selectedComponent as ListaComponentProps).alinhamento}
                    setAlinhamento={(alinhamento) => updateComponent(selectedComponent.id, { alinhamento })}
                    tamanho={(selectedComponent as ListaComponentProps).tamanho}
                    setTamanho={(tamanho) => updateComponent(selectedComponent.id, { tamanho })}
                    onDelete={handleDelete}
                    onDuplicate={handleDuplicate}
                />
            )}
            {selectedComponent?.type === 'contador' && (
                <ContadorDrawer
                    isOpen={isDrawerOpen}
                    onClose={() => setIsDrawerOpen(false)}
                    tipo={(selectedComponent as ContadorComponentProps).tipo}
                    setTipo={(tipo) => updateComponent(selectedComponent.id, { tipo })}
                    time={(selectedComponent as ContadorComponentProps).time}
                    setTime={(time) => updateComponent(selectedComponent.id, { time })}
                    textActive={(selectedComponent as ContadorComponentProps).textActive}
                    setTextActive={(textActive) => updateComponent(selectedComponent.id, { textActive })}
                    textFinalizado={(selectedComponent as ContadorComponentProps).textFinalizado}
                    setTextFinalizado={(textFinalizado) => updateComponent(selectedComponent.id, { textFinalizado })}
                    textColor={(selectedComponent as ContadorComponentProps).textColor}
                    setTextColor={(textColor) => updateComponent(selectedComponent.id, { textColor })}
                    bgColor={(selectedComponent as ContadorComponentProps).bgColor}
                    setBgColor={(bgColor) => updateComponent(selectedComponent.id, { bgColor })}
                    onDelete={handleDelete}
                    onDuplicate={handleDuplicate}
                />
            )}
            {selectedComponent?.type === 'depoimento' && (
                <DepoimentoDrawer
                    isOpen={isDrawerOpen}
                    onClose={() => setIsDrawerOpen(false)}
                    photo={(selectedComponent as DepoimentoComponentProps).photo}
                    setPhoto={(photo) => updateComponent(selectedComponent.id, { photo })}
                    depoimento={(selectedComponent as DepoimentoComponentProps).depoimento}
                    setDepoimento={(depoimento) => updateComponent(selectedComponent.id, { depoimento })}
                    estrelas={(selectedComponent as DepoimentoComponentProps).estrelas}
                    setEstrelas={(estrelas) => updateComponent(selectedComponent.id, { estrelas })}
                    nome={(selectedComponent as DepoimentoComponentProps).nome}
                    setNome={(nome) => updateComponent(selectedComponent.id, { nome })}
                    corFundo={(selectedComponent as DepoimentoComponentProps).corFundo}
                    setCorFundo={(corFundo) => updateComponent(selectedComponent.id, { corFundo })}
                    corTexto={(selectedComponent as DepoimentoComponentProps).corTexto}
                    setCorTexto={(corTexto) => updateComponent(selectedComponent.id, { corTexto })}
                    modoHorizontal={(selectedComponent as DepoimentoComponentProps).modoHorizontal}
                    setModoHorizontal={(modoHorizontal) => updateComponent(selectedComponent.id, { modoHorizontal })}
                    onDelete={handleDelete}
                    onDuplicate={handleDuplicate}
                />
            )}
        </>
    );
}