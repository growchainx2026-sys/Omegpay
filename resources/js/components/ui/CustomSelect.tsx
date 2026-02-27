import {
    Menu,
    MenuButton,
    MenuList,
    MenuItem,
    Button,
    VStack,
    Text
} from "@chakra-ui/react"
import { ChevronDownIcon } from "@chakra-ui/icons"

export interface CustomSelectProps {
    label: string
    options: { label: string, value: string }[]
    value: string
    onChange: (value: string) => void
}
export function CustomSelect({ label, options, value, onChange }: CustomSelectProps) {

    return (
        <VStack w={'100%'}>
            <Text>{label}</Text>

            <Menu >
                <MenuButton 
                as={Button} 
                rightIcon={<ChevronDownIcon />} 
                bg="gray.700" 
                color="white" 
                w={'100%'} 
                textAlign={'start'}
                _hover={{ bg: "gray.600" }}
                _active={{ bg: "gray.500" }}
                >
                    {value || "Selecione um item"}
                </MenuButton>
                <MenuList bg="gray.800" color="white">
                    {options.map((option) => (
                        <MenuItem
                            key={option.value}
                            bg="gray.800"
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
