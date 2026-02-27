import { Box, Button, FormControl, FormLabel, Input, Textarea, VStack, HStack, Icon, Switch, Drawer, DrawerOverlay, DrawerContent, DrawerHeader, IconButton, Text, DrawerBody, Slider, SliderTrack, SliderFilledTrack, SliderThumb, Image, Center } from '@chakra-ui/react'
import { Trash, Copy, Trash2, X, LucideCamera } from 'lucide-react'
import { OptionsDrawer } from '../ui/OptionsDrawer'
import { TextInput } from '../ui/TextInput'
import { ColorPicker } from '../ui/ColorPicker'

type DepoimentoDrawerProps = {
    isOpen: boolean
    onClose: () => void
    photo: string
    setPhoto: (photo: string) => void
    depoimento: string
    setDepoimento: (depoimento: string) => void
    estrelas: number
    setEstrelas: (estrelas: number) => void
    nome: string
    setNome: (nome: string) => void
    corFundo: string
    setCorFundo: (corFundo: string) => void
    corTexto: string
    setCorTexto: (corTexto: string) => void
    modoHorizontal: boolean
    setModoHorizontal: (modoHorizontal: boolean) => void
    onDelete: () => void
    onDuplicate: () => void
}

export function DepoimentoDrawer({
    isOpen,
    onClose,
    photo,
    setPhoto,
    depoimento,
    setDepoimento,
    estrelas,
    setEstrelas,
    nome,
    setNome,
    corFundo,
    setCorFundo,
    corTexto,
    setCorTexto,
    modoHorizontal,
    setModoHorizontal,
    onDelete,
    onDuplicate
}: DepoimentoDrawerProps) {

    const handleChangeImage = (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setPhoto(reader.result as string);
            };
            reader.readAsDataURL(file);
        }
    };

    return (
        <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">
            <DrawerOverlay />
            <DrawerContent bg="gray.800" maxW={'320px'}>
                <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
                    <OptionsDrawer title="Depoimento" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
                </DrawerHeader>

                <DrawerBody>
                    <VStack spacing={6} align="stretch" color="white">
                        <VStack spacing={4} align="stretch">
                            <FormControl >
                                <FormLabel>Foto</FormLabel>
                                <Center>
                                    {photo ? (
                                        <Image
                                            cursor={'pointer'} _hover={{ bg: 'gray.700' }}
                                            src={photo}
                                            border={'1px solid gray'}
                                            borderRadius={'100%'}
                                            p={2}
                                            alt="Depoimento"
                                            boxSize="100px"
                                            objectFit="cover"
                                            onClick={() => document.getElementById('input-upload-image-depoiment')?.click()}

                                        />
                                    ) : (
                                        <Center
                                            cursor={'pointer'} _hover={{ bg: 'gray.700' }}
                                            border={'1px solid gray'}
                                            borderRadius={'100%'}
                                            p={2}
                                            boxSize="100px"
                                            objectFit="cover"
                                            onClick={() => document.getElementById('input-upload-image-depoiment')?.click()}
                                        >
                                            <VStack
                                                w={'100%'}
                                                h={'100%'}
                                                spacing={1}
                                                alignItems="center"
                                                justifyContent="center"
                                                borderRadius={'100%'}
                                                bg={'gray.600'}>
                                                <Icon as={LucideCamera} color="gray.400" boxSize={8} />
                                                <Text as={"small"} fontSize={'10px'} color="gray.400">Upload foto</Text>
                                            </VStack>
                                        </Center>
                                    )}
                                </Center>
                                <Input id='input-upload-image-depoiment' type="file" accept="image/*" onChange={handleChangeImage} hidden />
                            </FormControl>
                            <FormControl>
                                <FormLabel>Depoimento</FormLabel>
                                <Textarea value={depoimento} onChange={(e) => setDepoimento(e.target.value)} />
                            </FormControl>
                            <VStack align="stretch" spacing={2}>
                                <Text>Estrelas{' '}({estrelas})</Text>
                                <Slider
                                    value={estrelas}
                                    min={1}
                                    max={5}
                                    step={1}
                                    onChange={setEstrelas}
                                >
                                    <SliderTrack>
                                        <SliderFilledTrack />
                                    </SliderTrack>
                                    <SliderThumb />
                                </Slider>
                            </VStack>

                            <TextInput
                                title='Nome'
                                value={nome}
                                onChange={(e) => setNome(e)}
                            />

                            <ColorPicker
                                title='Cor de fundo'
                                value={corFundo}
                                onChange={(e) => setCorFundo(e)}
                            />

                            <ColorPicker
                                title='Cor do texto'
                                value={corTexto}
                                onChange={(e) => setCorTexto(e)}
                            />
                            <FormControl display="flex" alignItems="center">
                                <FormLabel mb="0">Modo horizontal</FormLabel>
                                <Switch isChecked={modoHorizontal} onChange={(e) => {setModoHorizontal(e.target.checked);}} />
                            </FormControl>
                        </VStack>
                    </VStack>
                </DrawerBody>
            </DrawerContent>
        </Drawer >
    )
}