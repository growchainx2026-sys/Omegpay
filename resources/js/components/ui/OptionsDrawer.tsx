import { HStack, IconButton, Text } from "@chakra-ui/react";
import { Copy, Trash2, X } from "lucide-react";

export interface OptionsDrawerProps {title: string, onDelete: () => void, onDuplicate: () => void, onClose: () => void}

export const OptionsDrawer = ({title, onDelete, onDuplicate, onClose}: OptionsDrawerProps) => {

    return (
        <HStack justify="space-between" align="center">
            <Text color="white">{title}</Text>
            <HStack spacing={2}>
                <IconButton
                    aria-label="Excluir"
                    icon={<Trash2 />}
                    variant="ghost"
                    colorScheme="red"
                    border="none"
                    _hover={{
                        bg: 'transparent',
                    }}
                    size="xs"
                    onClick={onDelete}
                />
                <IconButton
                    aria-label="Duplicar"
                    icon={<Copy />}
                    variant="ghost"
                    border="none"
                    colorScheme="blue"
                    _hover={{
                        bg: 'transparent',
                    }}
                    size="xs"
                    onClick={onDuplicate}
                />
                <IconButton
                    aria-label="Fechar"
                    icon={<X />}
                    variant="ghost"
                    border="none"
                    colorScheme="whiteAlpha"
                    _hover={{
                        bg: 'transparent',
                    }}
                    size="xs"
                    onClick={onClose}
                />
            </HStack>
        </HStack>
    );
}