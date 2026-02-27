import { Button, HStack, IconButton, Text, VStack } from "@chakra-ui/react"
import { Copy, SquarePen, Trash2, Settings, Move } from "lucide-react"
import { useState } from "react"
import { useSortable } from "@dnd-kit/sortable"
import { CSS } from "@dnd-kit/utilities"

interface ComponentOptionsRowProps {
  onDelete: () => void
  onDuplicate: () => void
  onEdit: () => void
  id: string
  isDragDisabled?: boolean
  dragListeners?: any
}

export const ComponentOptionsRow = ({ onDelete, onDuplicate, onEdit, id, isDragDisabled = false, dragListeners }: ComponentOptionsRowProps) => {

    const [clicked, setClicked] = useState<boolean>(false);

    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging,
    } = useSortable({ 
        id, 
        disabled: isDragDisabled
    });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
        opacity: isDragging ? 0.5 : 1,
    };

    const onClickDelete = () => {
        setClicked(!clicked);
    }

    return (
        <HStack spacing={2} pos={'relative'}>
             <IconButton
                ref={setNodeRef}
                style={style}
                {...attributes}
                {...(dragListeners || listeners)}
                aria-label="Ordenar"
                icon={<Move />}
                variant="ghost"
                color={'white'}
                size="xs"
                cursor={isDragDisabled ? 'default' : 'grab'}
                _hover={{
                    bg: 'transparent',
                    borderColor: 'transparent'
                }}
                _active={{
                    cursor: 'grabbing'
                }}
            />
            <IconButton
                aria-label="Configurações de Grid"
                icon={<Settings />}
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
                aria-label="Duplicar"
                icon={<Copy />}
                variant="ghost"
                color={'white'}
                size="xs"
                _hover={{
                    bg: 'transparent',
                    borderColor: 'transparent'
                }}
                onClick={(e) => {
                    e.stopPropagation();
                    onDuplicate();
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
                            onClick={(e) => {
                                e.stopPropagation();
                                onDelete();
                                setClicked(false);
                            }}
                            colorScheme={'red'}
                        >Sim</Button>
                        <Button
                            size={'sm'}
                            onClick={(e) => {
                                e.stopPropagation();
                                onClickDelete();
                            }}
                            colorScheme={'white'}
                        >Não</Button>
                    </HStack>
                </VStack>
            )}
        </HStack>
    )
}