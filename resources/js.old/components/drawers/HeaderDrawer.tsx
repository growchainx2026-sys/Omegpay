import {
  Drawer,
  DrawerBody,
  DrawerHeader,
  DrawerOverlay,
  DrawerContent,
  DrawerCloseButton,
  HStack,
  Text,
  IconButton,
  VStack,
  Input,
  ButtonGroup,
  Button,
  useToast,
  Image,
  Box,
  Flex,
  Icon,
  Grid,
  GridItem,
  type RadioProps,
  useRadio,
  useRadioGroup,
  Slider,
  SliderTrack,
  SliderFilledTrack,
  SliderThumb,
} from '@chakra-ui/react'
import { Trash2, Copy, X, AlignLeft, AlignCenter, AlignRight, Edit2, UploadCloud, ImageIcon, Palette, VectorSquare } from 'lucide-react'
import { useRef, useState, type ChangeEvent, type DragEvent } from 'react'
import { OptionsDrawer } from '../ui/OptionsDrawer'
import { TextInput } from '../ui/TextInput'
import { ColorPicker } from '../ui/ColorPicker'

type HeaderDrawerProps = {
  isOpen: boolean
  onClose: () => void
  color: string
  setColor: (color: string) => void
  title: string
  setTitle: (title: string) => void
  url: string
  setUrl: (url: string) => void
  align: 'left' | 'right'
  setalign: (align: 'left' | 'right') => void
  fontSize: string | number
  setFontSize: (font_size: HeaderDrawerProps['fontSize']) => void
  image: string
  setImage: (image: string) => void
  padding: string | number
  setPadding: (padding: HeaderDrawerProps['padding']) => void
  background: any
  setBackground: (background: any) => void
  onDelete: () => void
  onDuplicate: () => void
}

type Submenu = 'image' | 'colors' | 'sizes';

const MAX_FILE_SIZE_MB = 10;

function RadioCard(props: RadioProps) {
  const { getInputProps, getRadioProps } = useRadio(props)

  const input = getInputProps()
  const checkbox = getRadioProps()

  return (
    <Box as='label'>
      <input {...input} />
      <Box
        {...checkbox}
        cursor='pointer'
        borderWidth='1px'
        borderRadius='md'
        boxShadow='md'
        _checked={{
          color: 'white',
          borderColor: 'teal.600',
        }}
        _focus={{
          boxShadow: 'outline',
        }}
        px={5}
        py={3}
      >
        {props.children}
      </Box>
    </Box>
  )
}


export function HeaderDrawer({
  isOpen,
  onClose,
  title,
  setTitle,
  color,
  setColor,
  background,
  setBackground,
  align,
  setalign,
  fontSize,
  setFontSize,
  image,
  setImage,
  padding,
  setPadding,
  onDelete,
  onDuplicate,
}: HeaderDrawerProps) {

  const [submenu, setSubmenu] = useState<Submenu>('image');
  const inputRef = useRef<HTMLInputElement>(null);
  const toast = useToast();

  const { getRootProps, getRadioProps } = useRadioGroup({
    name: 'submenu',
    defaultValue: submenu,
    onChange: (e) => setSubmenu(e as Submenu),
  })

  const group = getRootProps()


  const submenus = [
    { label: 'image', icon: ImageIcon },
    { label: 'colors', icon: Palette },
    { label: 'sizes', icon: VectorSquare }];

  const handleFile = (file: File) => {
    if (!file.type.startsWith("image/")) {
      toast({ status: "error", description: "Apenas imagens JPG ou PNG são permitidas." });
      return;
    }
    if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
      toast({ status: "error", description: `Tamanho máximo permitido: ${MAX_FILE_SIZE_MB}MB.` });
      return;
    }

    const reader = new FileReader();
    reader.onload = () => {
      setImage(reader.result as string);
      setImage(reader.result as string);
    };
    reader.readAsDataURL(file);
  };

  const handleDrop = (e: DragEvent<HTMLDivElement>) => {
    e.preventDefault();
    if (e.dataTransfer.files.length > 0) {
      handleFile(e.dataTransfer.files[0]);
    }
  };

  const handleFileInput = (e: ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) handleFile(file);
  };

  const handleRemove = () => {
    setImage(null);
  };

  const handleBrowse = () => {
    inputRef.current?.click();
  };

  return (
    <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">
      <DrawerOverlay />
      <DrawerContent bg="gray.800" maxW={'320px'}>
        <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
          <OptionsDrawer title="Header" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
        </DrawerHeader>

        <DrawerBody>
          <VStack spacing={6} align="stretch" color="white">
            <VStack align="stretch" spacing={2}>
              {!image ? (
                <Flex direction="column" align="center" justify="center" height="200px" cursor="pointer" onClick={handleBrowse} border={'2px dashed gray'} borderRadius={10} p={2}>
                  <UploadCloud size={36} color="#38A169" />
                  <Text mt={2} fontWeight="semibold">Arraste ou selecione o arquivo</Text>
                  <Text fontSize="sm" color="gray.400" textAlign={'center'}>
                    Solte os arquivos aqui ou clique para <Text as="span" color="teal.300" textDecoration="underline">buscar</Text> em seu computador
                  </Text>
                  <Text fontSize="xs" color="gray.500" mt={2}>
                    Formatos aceitos: JPG ou PNG. Tamanho máximo: 10MB
                  </Text>
                  <Input
                    ref={inputRef}
                    type="file"
                    accept="image/jpeg,image/png"
                    display="none"
                    onChange={handleFileInput}
                  />
                </Flex>
              ) : (
                <VStack textAlign="start" alignItems="flex-start" justifyContent={'flex-start'}>
                  <HStack justifyContent={'flex-start'} w={'150px'} position="relative">
                    <Image src={image} borderRadius="md" mx="auto" boxSize="150px" objectFit="cover" />
                    <HStack justify="center" gap={2} pos={'absolute'} top={0} bottom={0} right={0} left={0}>
                      <IconButton
                        size="sm"
                        color={'white'}
                        bg={'rgba(0, 0, 0, 0.9)'}
                        _hover={{
                          bg: 'rgba(0, 0, 0, 1)'
                        }}
                        borderRadius={'100%'}
                        aria-label="Editar imagem"
                        icon={<Icon as={Edit2} color="white" />}
                        onClick={handleBrowse}
                      />
                      <IconButton
                        size="sm"
                        color={'white'}
                        bg={'rgba(0, 0, 0, 0.9)'}
                        _hover={{
                          bg: 'rgba(0, 0, 0, 1)'
                        }}
                        borderRadius={'100%'}
                        aria-label="Remover imagem"
                        icon={<Icon as={Trash2} color="white" />}
                        onClick={handleRemove}
                      />
                    </HStack>
                  </HStack>
                  <Input
                    ref={inputRef}
                    type="file"
                    accept="image/jpeg,image/png"
                    display="none"
                    onChange={handleFileInput}
                  />
                  <Text fontSize="xs" color="gray.500" mt={2}>
                    Formatos aceitos: JPG ou PNG. Tamanho máximo: 10MB
                  </Text>
                </VStack>
              )}
            </VStack>

            <TextInput
              title='Nome do produto'
              value={title}
              onChange={setTitle}
            />



            <VStack align="start" spacing={2}>
              <Text>Alinhamento</Text>
              <ButtonGroup isAttached variant="outline" size="sm">
                <Button
                  color={'white'}
                  leftIcon={<AlignLeft size={16} />}
                  onClick={() => setalign('left')}
                  colorScheme={align === 'left' ? 'blue' : 'gray'}
                  bg={align === 'left' ? 'blue.500' : 'gray.700'}
                  _hover={{
                    color: 'white'
                  }}
                >
                  Esquerda
                </Button>

                <Button
                  color={'white'}
                  leftIcon={<AlignRight size={16} />}
                  onClick={() => setalign('right')}
                  colorScheme={align === 'right' ? 'blue' : 'gray'}
                  bg={align === 'right' ? 'blue.500' : 'gray.700'}
                  _hover={{
                    color: 'white'
                  }}
                >
                  Direita
                </Button>
              </ButtonGroup>
            </VStack>

            <VStack align="stretch" spacing={2}>
              <Text>Tamanho da fonte ({fontSize})</Text>
              <Slider
                value={fontSize as number}
                min={0}
                max={64}
                step={1}
                onChange={setFontSize}
              >
                <SliderTrack>
                  <SliderFilledTrack />
                </SliderTrack>
                <SliderThumb />
              </Slider>
            </VStack>

            <VStack align="stretch" spacing={2}>
              <Text>Largura da borda</Text>
              <Slider
                value={padding as number}
                min={0}
                max={10}
                step={1}
                onChange={setPadding}
              >
                <SliderTrack>
                  <SliderFilledTrack />
                </SliderTrack>
                <SliderThumb />
              </Slider>
            </VStack>

            <ColorPicker
              title='Cor do título'
              value={color}
              onChange={setColor}
            />

            <ColorPicker
              title='Cor do fundo'
              value={background}
              onChange={setBackground}
            />
          </VStack>
        </DrawerBody>
      </DrawerContent>
    </Drawer>
  )
}