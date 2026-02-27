import {
  Drawer,
  DrawerBody,
  DrawerHeader,
  DrawerOverlay,
  DrawerContent,
  HStack,
  Text,
  IconButton,
  VStack,
  Input,
  ButtonGroup,
  Button,
  useToast,
  Image,
  Flex,
  Icon,
  Modal,
  ModalOverlay,
  ModalContent,
  ModalHeader,
  ModalBody,
  ModalCloseButton,
  Slider,
  SliderTrack,
  SliderFilledTrack,
  SliderThumb,
} from '@chakra-ui/react'
import { Trash2, Copy, X, AlignLeft, AlignCenter, AlignRight, Edit2, UploadCloud, Crop } from 'lucide-react'
import { useEffect, useRef, useState, type ChangeEvent, type DragEvent } from 'react'
import { OptionsDrawer } from '../ui/OptionsDrawer'
import { TextInput } from '../ui/TextInput'


type ImageDrawerProps = {
  isOpen: boolean
  onClose: () => void
  url: string
  setUrl: (url: string) => void
  align: 'left' | 'center' | 'right'
  setalign: (align: 'left' | 'center' | 'right') => void
  size: number
  setSize: (size: number) => void
  redirectUrl: string
  setRedirectUrl: (url: string) => void
  onDelete: () => void
  onDuplicate: () => void
}


const MAX_FILE_SIZE_MB = 10;

export function ImageDrawer({
  isOpen,
  onClose,
  url,
  setUrl,
  align,
  setalign,
  size,
  setSize,
  redirectUrl,
  setRedirectUrl,
  onDelete,
  onDuplicate,
}: ImageDrawerProps) {

  const [image, setImage] = useState<string | null>(null);
  const [isResizeModalOpen, setResizeModalOpen] = useState(false); // Estado para a modal de redimensionamento
  const inputRef = useRef<HTMLInputElement>(null);
  const toast = useToast();


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
      setUrl(reader.result as string);
    };
    reader.readAsDataURL(file);
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

  const openResizeModal = () => {
    setResizeModalOpen(true);
  };

  const closeResizeModal = () => {
    setResizeModalOpen(false);
  };

  return (
    <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">
      <DrawerOverlay />
      <DrawerContent bg="gray.800" maxW={'320px'}>
        <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
          <OptionsDrawer title="Imagem" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
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
                      <IconButton
                        size="sm"
                        color={'white'}
                        bg={'rgba(0, 0, 0, 0.9)'}
                        _hover={{
                          bg: 'rgba(0, 0, 0, 1)'
                        }}
                        borderRadius={'100%'}
                        aria-label="Redimensionar imagem"
                        icon={<Icon as={Crop} color="white" />}
                        onClick={openResizeModal}
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

            <VStack align="start" spacing={2} w={'100%'}>
              <Text>Alinhamento</Text>
              <ButtonGroup isAttached variant="outline" size="sm" px={2} w={'100%'}>
                <Button
                  color={'white'}
                  leftIcon={<AlignLeft size={16} />}
                  onClick={() => setalign('left')}
                  colorScheme={align === 'left' ? 'blue' : 'gray'}
                  bg={align === 'left' ? 'blue.500' : 'gray.700'}
                  _hover={{
                    color: 'white'
                  }}
                  fontSize={10}
                >
                  Esquerda
                </Button>
                <Button
                  color={'white'}
                  leftIcon={<AlignCenter size={16} />}
                  onClick={() => setalign('center')}
                  colorScheme={align === 'center' ? 'blue' : 'gray'}
                  bg={align === 'center' ? 'blue.500' : 'gray.700'}
                  _hover={{
                    color: 'white'
                  }}
                  fontSize={10}
                >
                  Centro
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
                  fontSize={10}
                >
                  Direita
                </Button>
              </ButtonGroup>
            </VStack>

            <VStack align="stretch" spacing={2}>
              <Text>Largura ({size}px)</Text>
              <Slider
                value={size}
                min={250}
                max={1920}
                step={1}
                onChange={setSize}
              >
                <SliderTrack>
                  <SliderFilledTrack />
                </SliderTrack>
                <SliderThumb />
              </Slider>
            </VStack>

            {/* <VStack align="stretch" spacing={2}>
              <Text>Altura({height}px)</Text>
              <Slider
                value={height}
                min={0}
                max={800}
                step={1}
                onChange={setHeight}
              >
                <SliderTrack>
                  <SliderFilledTrack />
                </SliderTrack>
                <SliderThumb />
              </Slider>
            </VStack> */}

            <TextInput
              title='Url de redirecionamento'
              value={redirectUrl}
              onChange={(e) => setRedirectUrl(e)}
              placeholder='https://'
            />



          </VStack>
        </DrawerBody>
      </DrawerContent>

      {/* Modal para redimensionamento */}
      <Modal isOpen={isResizeModalOpen} onClose={closeResizeModal} size="xl" isCentered>
        <ModalOverlay />
        <ModalContent>
          <ModalHeader>Redimensionar Imagem</ModalHeader>
          <ModalCloseButton />
          <ModalBody>
            {/* Aqui você pode adicionar ferramentas de redimensionamento e recorte */}
            <Image src={image} alt="Imagem para redimensionar" />
            <Text mt={4}>Ferramentas de redimensionamento e recorte serão implementadas aqui.</Text>
          </ModalBody>
        </ModalContent>
      </Modal>
    </Drawer>
  )
}