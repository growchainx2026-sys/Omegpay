import { Button, HStack, IconButton, Text, VStack } from "@chakra-ui/react"
import { SquarePen, Trash2 } from "lucide-react"
import { useState } from "react"

export const ComponentOptionsComponent = ({ onDelete, onEdit }: { onDelete: () => void, onEdit: () => void }) => {

    const [clicked, setClicked] = useState<boolean>(false);

    const onClickDelete = () => {
        setClicked(!clicked);
    }

    return (
        <HStack spacing={2} pos={'relative'}>
            <IconButton
                aria-label="Editar Componente"
                icon={<SquarePen />}
                variant="ghost"
                color={'white'}
                size="xs"
                _hover={{
                    bg: 'transparent',
                    borderColor: 'transparent'
                }}
                onClick={(e) => {
                    e.stopPropagation();
                    onEdit();
                }}
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
                onClick={(e) => {
                    e.stopPropagation();
                    onClickDelete();
                }}
            />
            {clicked && (
                <VStack
                    position="absolute"
                    top="100%"
                    right={0}
                    bg="gray.800"
                    p={2}
                    borderRadius="md"
                    boxShadow="lg"
                    zIndex={1000}
                    spacing={1}
                    minW="120px"
                >
                    <Text fontSize="xs" color="white" textAlign="center">
                        Tem certeza?
                    </Text>
                    <HStack spacing={1}>
                        <Button
                            size="xs"
                            colorScheme="red"
                            onClick={(e) => {
                                e.stopPropagation();
                                onDelete();
                                setClicked(false);
                            }}
                        >
                            Sim
                        </Button>
                        <Button
                            size="xs"
                            variant="ghost"
                            color="white"
                            onClick={(e) => {
                                e.stopPropagation();
                                setClicked(false);
                            }}
                        >
                            Não
                        </Button>
                    </HStack>
                </VStack>
            )}
        </HStack>
    )
}