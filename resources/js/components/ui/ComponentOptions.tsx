import { Button, HStack, IconButton, Text, VStack } from "@chakra-ui/react"
import { Copy, SquarePen, Trash2 } from "lucide-react"
import { useState } from "react"

export const ComponentOptions = ({ onDelete, onDuplicate, onEdit }: { onDelete: () => void, onDuplicate: () => void, onEdit: () => void }) => {

    const [clicked, setClicked] = useState<boolean>(false);

    const onClickDelete = () => {
        setClicked(!clicked);
    }

    return (
        <HStack spacing={2} pos={'relative'}>
            <IconButton
                aria-label="Editar"
                icon={<SquarePen />}
                variant="ghost"
                color={'white'}
                size="xs"
                _hover={{
                    bg: 'transparent',
                    borderColor: 'transparent'
                }}
                onClick={onEdit}
            />
            <IconButton
                aria-label="Duplicar"
                icon={<Copy />}
                variant="ghost"
                color={'white'}
                size="xs"
                _hover={{
                    bg: 'transparent',
                    borderColor: 'transparent'
                }}
                onClick={onDuplicate}
            />
            <IconButton
                aria-label="Excluir"
                icon={<Trash2 />}
                variant="ghost"
                color={'white'}
                size="xs"
                _hover={{
                    bg: 'transparent',
                    borderColor: 'transparent'
                }}
                onClick={onClickDelete}
            />

            {clicked && (
                <VStack
                    pos={'absolute'}
                    top={8}
                    right={-2}
                    p={2}
                    borderRadius={8}
                    bg={'black'}
                >
                    <Text color={'white'}>Confirmar?</Text>
                    <HStack
                        gap={2}>
                        <Button
                            size={'sm'}
                            onClick={onDelete}
                            colorScheme={'red'}
                        >Sim</Button>
                        <Button
                            size={'sm'}
                            onClick={onClickDelete}
                            colorScheme={'white'}
                        >Não</Button>
                    </HStack>
                </VStack>
            )}
        </HStack>
    )
}