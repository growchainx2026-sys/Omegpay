import {
    Drawer,
    DrawerBody,
    DrawerHeader,
    DrawerContent,
    IconButton,
    VStack,
    HStack,
    Input,
    Text,
    Select,
    Switch,
    Button,
    Slider,
    SliderTrack,
    SliderFilledTrack,
    SliderThumb,
    ButtonGroup,
} from '@chakra-ui/react'
import { Trash2, Copy, X, AlignLeft, AlignCenter, AlignRight } from 'lucide-react'
import { CustomSelect } from '../ui/CustomSelect'
import { OptionsDrawer } from '../ui/OptionsDrawer'
import { SelectInput } from '../ui/SelectInput'
import { TextInput } from '../ui/TextInput'
import { ColorPicker } from '../ui/ColorPicker'

type ListaDrawerProps = {
    isOpen: boolean
    onClose: () => void
    icone: 'nenhum' | 'check' | 'decimal' | 'circulo'
    setIcone: (icone: any) => void
    corIcone: string
    setCorIcone: (corIcone: string) => void
    temTitulo: boolean
    setTemTitulo: (temTitulo: boolean) => void
    titulo?: string
    setTitulo?: (titulo: string) => void
    corFundo: string
    setCorFundo: (corFundo: string) => void
    corTexto: string
    setCorTexto: (corTexto: string) => void
    alinhamento: 'left' | 'center' | 'right'
    setAlinhamento: (alinhamento: 'left' | 'center' | 'right') => void
    tamanho: number
    setTamanho: (tamanho: number) => void
    items: string[]
    setItems: (items: string[]) => void
    onDelete: () => void
    onDuplicate: () => void
}

export function ListaDrawer({
    isOpen, onClose,
    icone, setIcone,
    corIcone, setCorIcone,
    temTitulo, setTemTitulo,
    titulo, setTitulo,
    corFundo, setCorFundo,
    corTexto, setCorTexto,
    alinhamento, setAlinhamento,
    tamanho, setTamanho,
    items, setItems,
    onDelete, onDuplicate
}: ListaDrawerProps) {

    const handleAddItem = () => {
        if (items.length < 10) {
            setItems([...items, `Item ${items.length + 1}`])
        }
    }

    const handleItemChange = (index: number, value: string) => {
        const novos = [...items]
        novos[index] = value
        setItems(novos)
    }

    const handleRemoveItem = (index: number) => {
        setItems(items.filter((_, i) => i !== index))
    }

    return (
        <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">
            <DrawerContent bg="gray.800" maxW={'320px'}>
                <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
                    <OptionsDrawer title="Lista" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
                </DrawerHeader>

                <DrawerBody color="white">
                    <VStack spacing={4} align="stretch" my={2}>
                        {/* Ícone */}
                        <SelectInput
                            label='Ícone'
                            options={[
                                { label: 'Nenhum', value: 'nenhum' },
                                { label: 'Check', value: 'check' },
                                { label: 'Decimal', value: 'decimal' },
                                { label: 'Círculo', value: 'circulo' }
                            ]}
                            value={icone}
                            onChange={setIcone}
                        />
                        <ColorPicker
                        title='Cor do ícone'
                        value={corIcone}
                        onChange={setCorIcone}
                        />

                        {/* Título */}
                        <VStack align="stretch" spacing={4}>
                            <HStack gap={2}>
                                <Switch isChecked={temTitulo} onChange={(e) => setTemTitulo(e.target.checked)} />
                                <Text>Título</Text>
                            </HStack>
                            {temTitulo && (
                                <TextInput
                                    title=''
                                    value={titulo as string}
                                    onChange={(e) => setTitulo(e)}
                                    placeholder="Digite o título"
                                />
                            )}
                        </VStack>

                        {/* Cores */}
                        <ColorPicker
                        title='Cor de fundo'
                        value={corFundo}
                        onChange={setCorFundo}
                        />
                        <ColorPicker
                        title='Cor do texto'
                        value={corTexto}
                        onChange={setCorTexto}
                        />
                        {/* Alinhamento */}
                        <VStack align="start" spacing={2}>
                            <Text>Alinhamento</Text>
                            <ButtonGroup isAttached variant="outline" size="sm">
                                <Button
                                    color={'white'}
                                    leftIcon={<AlignLeft size={16} />}
                                    onClick={() => setAlinhamento('left')}
                                    colorScheme={alinhamento === 'left' ? 'blue' : 'gray'}
                                    bg={alinhamento === 'left' ? 'blue.500' : 'gray.700'}
                                    _hover={{
                                        color: 'white'
                                    }}
                                    fontSize={12}
                                >
                                    Esquerda
                                </Button>
                                <Button
                                    color={'white'}
                                    leftIcon={<AlignCenter size={16} />}
                                    onClick={() => setAlinhamento('center')}
                                    colorScheme={alinhamento === 'center' ? 'blue' : 'gray'}
                                    bg={alinhamento === 'center' ? 'blue.500' : 'gray.700'}
                                    _hover={{
                                        color: 'white'
                                    }}
                                    fontSize={12}
                                >
                                    Centro
                                </Button>
                                <Button
                                    color={'white'}
                                    leftIcon={<AlignRight size={16} />}
                                    onClick={() => setAlinhamento('right')}
                                    colorScheme={alinhamento === 'right' ? 'blue' : 'gray'}
                                    bg={alinhamento === 'right' ? 'blue.500' : 'gray.700'}
                                    _hover={{
                                        color: 'white'
                                    }}
                                    fontSize={12}
                                >
                                    Direita
                                </Button>
                            </ButtonGroup>
                        </VStack>

                        {/* Tamanho */}
                        <VStack align="stretch" spacing={2}>
                            <Text>Tamanho</Text>
                            <Slider
                                value={tamanho}
                                min={12}
                                max={64}
                                step={1}
                                onChange={setTamanho}
                            >
                                <SliderTrack>
                                    <SliderFilledTrack />
                                </SliderTrack>
                                <SliderThumb />
                            </Slider>
                        </VStack>

                        {/* Itens */}
                        <VStack align="stretch" spacing={2}>
                            <Text>Itens</Text>
                            {items.map((item, index) => (
                                <HStack key={index}>
                                    <Input value={item} onChange={(e) => handleItemChange(index, e.target.value)} bg="gray.700" />
                                    <IconButton icon={<Trash2 size={16} />} aria-label="Remover" size="sm" onClick={() => handleRemoveItem(index)} />
                                </HStack>
                            ))}
                            <HStack justify="space-between">
                                <Button size="sm" colorScheme="green" onClick={handleAddItem} isDisabled={items.length >= 10}>
                                    Adicionar
                                </Button>
                                <Text fontSize="sm">{`${items.length} / 10`}</Text>
                            </HStack>
                        </VStack>
                    </VStack>
                </DrawerBody>
            </DrawerContent>
        </Drawer>
    )
}
