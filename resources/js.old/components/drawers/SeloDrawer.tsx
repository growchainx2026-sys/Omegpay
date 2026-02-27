import { Drawer, DrawerBody, DrawerHeader,  DrawerContent,  HStack, Text, IconButton, VStack, Input, useRadio, Box, type RadioProps, useRadioGroup, Grid, GridItem, ButtonGroup, Button } from '@chakra-ui/react'
import { Trash2, Copy, X, AlignCenter, AlignLeft, AlignRight } from 'lucide-react'
import SeloModelo1 from '../ui/SeloModelo1'
import SeloModelo2 from '../ui/SeloModelo2'
import SeloModelo3 from '../ui/SeloModelo3'
import { OptionsDrawer } from '../ui/OptionsDrawer'
import { TextInput } from '../ui/TextInput'
import { ColorPicker } from '../ui/ColorPicker'

type Align = 'left' | 'center' | 'right';
type Selo = '1' | '2' | '3';

type SeloDrawerProps = {
  isOpen: boolean
  onClose: () => void
  selo: any
  setSelo: (selo: Selo) => void
  header: string
  setHeader: (header: string) => void
  title: string
  setTitle: (title: string) => void
  subtitle: string
  setSubtitle: (subtitle: string) => void
  title_color: string
  setTitleColor: (title_color: string) => void
  color: string
  setColor: (color: string) => void
  align: Align
  setAlign: (align: Align) => void
  onDelete: () => void
  onDuplicate: () => void
}

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

export const selos = [
  { label: '1', value: SeloModelo1 },
  { label: '2', value: SeloModelo2 },
  { label: '3', value: SeloModelo3 }
];

export function SeloDrawer({
  isOpen,
  onClose,
  selo,
  setSelo,
  title,
  setTitle,
  header,
  setHeader,
  subtitle,
  setSubtitle,
  title_color,
  setTitleColor,
  color,
  setColor,
  align,
  setAlign,
  onDelete,
  onDuplicate
}: SeloDrawerProps) {



  const { getRootProps, getRadioProps } = useRadioGroup({
    name: 'selo',
    defaultValue: selo,
    value: selo,
    onChange: (e) => setSelo(e as Selo),
  })

  const group = getRootProps()


  return (
    <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">

      <DrawerContent bg="gray.800" maxW={'320px'}>
        <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
          <OptionsDrawer title="Selo" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
        </DrawerHeader>

        <DrawerBody>
          <VStack spacing={6} align="stretch" color="white">
            <VStack align="stretch" spacing={2}>
              <Grid {...group} templateColumns="repeat(3, 1fr)" gap={3}>
                {selos.map(({ label, value: Svg }) => {
                  const radio = getRadioProps({ value: label });
                  return (
                    <GridItem
                      key={label} // ✅ key deve estar aqui!
                      colSpan={1}
                      display={'flex'}
                      alignItems={'center'}
                      justifyContent={'center'}
                      w={'100%'}
                    >
                      <RadioCard {...radio}>
                        
                        <Svg {...{ header, title: label == '1' ? title : label == '2' ? '100%' : 'DIAS', subtitle: label == '1' ? subtitle : 'DE GARANTIA', title_color, color: label == '1' ? "#4593f9" : label == '2' ? '#f9454b' : '#ffbf00', width: '100%' }} />
                      </RadioCard>
                    </GridItem>
                  );
                })}
              </Grid>
            </VStack>

            {selo === '3' && (
              <TextInput
              title='Texto Superior'
              value={header}
              onChange={setHeader}
              />    
            )}

            <TextInput
              title='Título'
              value={title}
              onChange={setTitle}
              />

            <TextInput
              title='Subtítulo'
              value={subtitle}
              onChange={setSubtitle}
              />

            <ColorPicker
            title='Cor principal'
            value={color}
            onChange={setColor}
            />

<ColorPicker
            title='Cor do título'
            value={title_color}
            onChange={setTitleColor}
            />

            <VStack align="start" spacing={2}>
              <Text>Alinhamento</Text>
              <ButtonGroup isAttached variant="outline" size="sm">
                <Button
                  color={'white'}
                  leftIcon={<AlignLeft size={16} />}
                  onClick={() => setAlign('left')}
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
                  onClick={() => setAlign('center')}
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
                  onClick={() => setAlign('right')}
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
          </VStack>
        </DrawerBody>
      </DrawerContent>
    </Drawer>
  )
}