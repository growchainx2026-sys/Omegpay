import { Drawer, DrawerBody, DrawerHeader, DrawerContent, HStack, Text, IconButton, VStack, Input, Grid } from '@chakra-ui/react'
import { Trash2, Copy, X } from 'lucide-react'
import { CustomSelect } from '../ui/CustomSelect'
import { OptionsDrawer } from '../ui/OptionsDrawer'
import { SelectInput } from '../ui/SelectInput'
import { TextInput } from '../ui/TextInput'
import { ColorPicker } from '../ui/ColorPicker'

type TextDrawerProps = {
  isOpen: boolean
  onClose: () => void
  tipo: 'data' | 'time'
  setTipo: (tipo: 'data' | 'time') => void
  time: any
  setTime: (time: any) => void
  textActive: string
  setTextActive: (text: string) => void
  textFinalizado: string
  setTextFinalizado: (text: string) => void
  textColor: string
  setTextColor: (color: string) => void
  bgColor: string
  setBgColor: (color: string) => void
  onDelete: () => void
  onDuplicate: () => void
}

export function ContadorDrawer({
  isOpen,
  onClose,
  tipo,
  setTipo,
  time,
  setTime,
  textActive,
  setTextActive,
  textFinalizado,
  setTextFinalizado,
  textColor,
  setTextColor,
  bgColor,
  setBgColor,
  onDelete,
  onDuplicate
}: TextDrawerProps) {
  return (
    <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">

      <DrawerContent bg="gray.800" maxW={'320px'}>
        <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
          <OptionsDrawer title="Cronômetro" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
        </DrawerHeader>

        <DrawerBody>
          <VStack spacing={6} align="stretch" color="white" my={2}>
            <SelectInput
              label="Tipo"
              options={[
                { value: 'data', label: 'Data Final' },
                { value: 'time', label: 'Tempo em minutos' }
              ]}
              value={tipo}
              onChange={(value) => setTipo(value as 'data' | 'time')}
            />
            <TextInput
              type={tipo === 'data' ? 'datetime-local' : 'time'}
              title={tipo === 'data' ? 'Data' : 'Tempo'}
              value={time}
              onChange={(e) => setTime(e)}
            />

            <Grid
              templateColumns={'repeat(2, 1fr)'}
              gap={4}
            >
              <ColorPicker
                title='Cor fundo'
                value={bgColor}
                onChange={(e) => setBgColor(e)}
              />
              <ColorPicker
                title='Cor texto'
                value={textColor}
                onChange={(e) => setTextColor(e)}
              />
            </Grid>

            <TextInput
              title='Texto contagem regressiva'
              value={textActive}
              onChange={(e) => setTextActive(e)}
            />

            <TextInput
              title='Texto contagem finalizado'
              value={textFinalizado}
              onChange={(e) => setTextFinalizado(e)}
            />
          </VStack>
        </DrawerBody>
      </DrawerContent>
    </Drawer>
  )
}