import {
    Menu,
    MenuButton,
    MenuList,
    MenuItem,
    Button,
    VStack,
    Text,
    Box
} from "@chakra-ui/react"
import { ChevronDownIcon } from "@chakra-ui/icons"
import useConfig from "../../stores/config"

export interface SelectInputProps {
    label: string
    options: { label: string, value: string }[]
    value: string
    onChange: (value: string) => void
}
export function SelectInput({ label, options, value, onChange }: SelectInputProps) {

    const { config } = useConfig();

    return (
        <VStack
            w={'100%'}
            borderWidth={1}
            borderColor={'gray.400'}
            borderRadius={7}
            h={'40px'}
            pos={'relative'}
            >
            <Box
                pos={'absolute'} top={'-10px'} px={1} bg={config.bg_color} left={2} zIndex={2}
            >
                <Text fontSize={12} color={'gray.200'} fontWeight={'bold'} bg={'transparent'}>{label}</Text>
            </Box>

            <Menu >
                <MenuButton
                    as={Button}
                    rightIcon={<ChevronDownIcon />}
                    bg="transparent"
                    color="white"
                    w={'100%'}
                    textAlign={'start'}
                    _hover={{ bg: "transparent" }}
                    _active={{ bg: "transparent" }}
                >
                    {value || "Selecione um item"}
                </MenuButton>
                <MenuList bg="transparent" color="white">
                    {options.map((option) => (
                        <MenuItem
                            key={option.value}
                            bg="gray.800"
                            border={'none'}
                            borderRadius={0}
                            _hover={{ bg: "teal" }}
                            onClick={() => onChange(option.value)}
                        >
                            {option.label}
                        </MenuItem>
                    ))}
                </MenuList>
            </Menu>
        </VStack>
    )
}
