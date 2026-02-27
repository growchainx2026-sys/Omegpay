import { Button } from "@chakra-ui/react"

export interface ComponentButtonProps {
    icon: any
    label: string
    dragItem: string
}

export const ComponentButton = ({['icon']: Icon, label, dragItem}: ComponentButtonProps) => {

    return (
        <Button
            height="100px"
            bg="transparent"
            borderRadius={10}
            borderWidth={2}
            borderColor={'gray.700'}
            _hover={{
                bg: 'transparent',
                borderColor: 'teal'
            }}
            _focusVisible={{
                borderColor: 'teal'
            }}
            _selected={{
                borderColor: 'teal'
            }}
            color="white"
            fontSize="sm"
            display="flex"
            flexDir="column"
            gap={2}
            draggable="true"
            onDragStart={(e) => {
                e.dataTransfer.setData('text/plain', dragItem)
            }}
        >
            <Icon size={24} />
            {label}
        </Button>
    )
}