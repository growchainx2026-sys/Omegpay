import { Drawer, DrawerBody, DrawerHeader, DrawerContent, HStack, Text, IconButton, VStack, Input, Select, useRadio, Box, type RadioProps, useRadioGroup, Grid, GridItem, Icon } from '@chakra-ui/react'
import { Trash2, Copy, X, SignalHigh, MessageSquareText, MousePointerClick, Cloud, ArrowDownToLine, File, Heart, Users, Play, BadgeCheck, Globe, AtSign } from 'lucide-react'
import { OptionsDrawer } from '../ui/OptionsDrawer'
import { TextInput } from '../ui/TextInput'
import { ColorPicker } from '../ui/ColorPicker'
import { SelectInput } from '../ui/SelectInput'

type TextDrawerProps = {
  isOpen: boolean
  onClose: () => void
  icon: any
  setIcon: (icon: any) => void
  title: string
  setTitle: (title: string) => void
  subtitle: string
  setSubtitle: (subtitle: string) => void
  title_color: string
  setTitleColor: (title_color: string) => void
  subtitle_color: string
  setSubtitleColor: (subtitle_color: string) => void
  mode: 'vertical' | 'horizontal'
  setMode: (mode: 'vertical' | 'horizontal') => void
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
          bg: 'teal.600',
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

export const icons = [
  { label: 'alt-sign', value: AtSign },
  { label: 'sinal-high', value: SignalHigh },
  { label: 'message-square-text', value: MessageSquareText },
  { label: 'mouse-pointer-click', value: MousePointerClick },
  { label: 'cloud', value: Cloud },
  { label: 'arrow-down-to-line', value: ArrowDownToLine },
  { label: 'file', value: File },
  { label: 'heart', value: Heart },
  { label: 'users', value: Users },
  { label: 'play', value: Play },
  { label: 'badge-check', value: BadgeCheck },
  { label: 'globe', value: Globe }
];

export function VantagemDrawer({
  isOpen,
  onClose,
  icon,
  setIcon,
  title,
  setTitle,
  subtitle,
  setSubtitle,
  title_color,
  setTitleColor,
  subtitle_color,
  setSubtitleColor,
  mode,
  setMode,
  onDelete,
  onDuplicate
}: TextDrawerProps) {



  const { getRootProps, getRadioProps } = useRadioGroup({
    name: 'icon',
    defaultValue: icon,
    onChange: (e) => setIcon(e),
  })

  const group = getRootProps()


  return (
    <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">

      <DrawerContent bg="gray.800" maxW={'320px'}>
        <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
          <OptionsDrawer title="Vantagem" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
        </DrawerHeader>

        <DrawerBody>
          <VStack spacing={6} align="stretch" color="white">
            <VStack align="stretch" spacing={2}>
              <Text>Ícone</Text>
              <Grid {...group} templateColumns="repeat(3, 1fr)" gap={3}>
                {icons.map(({ label, ['value']: IconItem }) => {
                  const radio = getRadioProps({ value: label })
                  return (
                    <GridItem
                      key={label}
                      colSpan={1}
                      display={'flex'}
                      alignItems={'center'}
                      justifyContent={'center'}
                      w={'100%'}
                    >
                      <RadioCard key={label} {...radio}>
                        <Icon as={IconItem} fontSize={'36px'} />
                      </RadioCard>
                    </GridItem>
                  )
                })}
              </Grid>
            </VStack>
            <TextInput
              title='Titulo'
              value={title}
              onChange={setTitle}
            />
            <TextInput
                title='Subtítulo'
                value={subtitle}
                onChange={setSubtitle}
              />
              
            <ColorPicker
            title="Cor do título"
            value={title_color}
            onChange={setTitleColor}
            />
            <ColorPicker
            title="Cor do subtítulo"
            value={subtitle_color}
            onChange={setSubtitleColor}
            />

            <SelectInput
            label='Modo'
            options={[
              { label: 'Vertical', value: 'vertical' },
              { label: 'Horizontal', value: 'horizontal' }
            ]}
            value={mode}
            onChange={(e) => setMode(e as 'vertical' | 'horizontal')}
            />
          </VStack>
        </DrawerBody>
      </DrawerContent>
    </Drawer>
  )
}