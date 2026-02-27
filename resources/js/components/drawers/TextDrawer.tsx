import { Drawer, DrawerBody, DrawerHeader, DrawerContent, HStack, Text, IconButton, VStack, Input, Slider, SliderTrack, SliderFilledTrack, SliderThumb } from '@chakra-ui/react'
import { Trash2, Copy, X } from 'lucide-react'
import { OptionsDrawer } from '../ui/OptionsDrawer'
import { TextInput } from '../ui/TextInput'
import { ColorPicker } from '../ui/ColorPicker'

type TextDrawerProps = {
  isOpen: boolean
  onClose: () => void
  text: string
  setText: (text: string) => void
  color: string
  setColor: (color: string) => void
  fontSize: number
  setFontSize: (size: number) => void
  borderColor: string
  setBorderColor: (color: string) => void
  backgroundColor: string
  setBackgroundColor: (color: string) => void
  borderWidth: number
  setBorderWidth: (width: number) => void
  borderRadius: number
  setBorderRadius: (radius: number) => void
  onDelete: () => void
  onDuplicate: () => void
}

export function TextDrawer({
  isOpen,
  onClose,
  text,
  setText,
  color,
  setColor,
  fontSize,
  setFontSize,
  borderColor,
  setBorderColor,
  backgroundColor,
  setBackgroundColor,
  borderWidth,
  setBorderWidth,
  borderRadius,
  setBorderRadius,
  onDelete,
  onDuplicate
}: TextDrawerProps) {
  return (
    <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">
      
      <DrawerContent bg="gray.800" maxW={'320px'}>
        <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
          <OptionsDrawer title="Texto" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
        </DrawerHeader>

        <DrawerBody>
          <VStack spacing={6} align="stretch" color="white">
            <TextInput
            title="Texto"
            value={text}
            onChange={(e) => setText(e)}
            />
            <ColorPicker
            title="Cor de fundo"
            value={backgroundColor}
            onChange={(e) => setBackgroundColor(e)}
            />
            <ColorPicker
            title="Cor da borda"
            value={borderColor}
            onChange={(e) => setBorderColor(e)}
            />
            <ColorPicker
            title="Cor do texto"
            value={color}
            onChange={(e) => setColor(e)}
            />
            
            <VStack align="stretch" spacing={2}>
              <Text>Tamanho da fonte</Text>
              <Slider
                value={fontSize}
                min={12}
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
                value={borderWidth}
                min={0}
                max={10}
                step={1}
                onChange={setBorderWidth}
              >
                <SliderTrack>
                  <SliderFilledTrack />
                </SliderTrack>
                <SliderThumb />
              </Slider>
            </VStack>

            <VStack align="stretch" spacing={2}>
              <Text>Raio da borda</Text>
              <Slider
                value={borderRadius}
                min={0}
                max={20}
                step={1}
                onChange={setBorderRadius}
              >
                <SliderTrack>
                  <SliderFilledTrack />
                </SliderTrack>
                <SliderThumb />
              </Slider>
            </VStack>
          </VStack>
        </DrawerBody>
      </DrawerContent>
    </Drawer>
  )
}